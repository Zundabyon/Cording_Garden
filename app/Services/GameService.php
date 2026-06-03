<?php

namespace App\Services;

use App\Models\AdminSetting;
use App\Models\Affection;
use App\Models\Character;
use App\Models\DailyLog;
use App\Models\GameSave;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;

class GameService
{
    // Game starts Jan 8 (Monday) - 3rd semester
    const GAME_START_DATE = '2024-01-08';
    const TOTAL_DAYS = 90;

    public function getCurrentGameState(User $user): array
    {
        $save = $user->gameSave;

        if (!$save) {
            return ['has_save' => false];
        }

        $dayOfWeek = $this->calculateDayOfWeek($save->current_day);
        $isWeekend = in_array($dayOfWeek, ['saturday', 'sunday']);
        $gameDate = $this->calculateGameDate($save->current_day);

        $characters = Character::orderBy('sort_order')->get();
        $characterData = $characters->map(function ($char) use ($user) {
            $affection = $user->affections()->where('character_id', $char->id)->first();
            return [
                'character' => $char,
                'affection' => $affection ? $affection->level : 0,
            ];
        });

        return [
            'has_save' => true,
            'save' => $save,
            'current_day' => $save->current_day,
            'total_days' => self::TOTAL_DAYS,
            'day_of_week' => $dayOfWeek,
            'day_of_week_label' => $this->getDayOfWeekLabel($dayOfWeek),
            'is_weekend' => $isWeekend,
            'phase' => $save->phase,
            'phase_label' => $this->getPhaseLabel($save->phase),
            'game_date' => $gameDate,
            'available_actions' => $this->getAvailableActions($user, $save),
            'characters' => $characterData,
            'user' => $user->fresh(),
            'is_game_over' => $save->current_day > self::TOTAL_DAYS,
        ];
    }

    public function calculateDayOfWeek(int $day): string
    {
        // Day 1 = Monday Jan 8
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        return $days[($day - 1) % 7];
    }

    public function getDayOfWeekLabel(string $day): string
    {
        return match($day) {
            'monday' => '月曜日',
            'tuesday' => '火曜日',
            'wednesday' => '水曜日',
            'thursday' => '木曜日',
            'friday' => '金曜日',
            'saturday' => '土曜日',
            'sunday' => '日曜日',
            default => '？曜日',
        };
    }

    public function getPhaseLabel(string $phase): string
    {
        return match($phase) {
            'morning' => '朝',
            'afternoon' => '昼',
            'after_school' => '放課後',
            'night' => '夜（就寝）',
            default => $phase,
        };
    }

    public function calculateGameDate(int $day): array
    {
        $startDate = Carbon::parse(self::GAME_START_DATE);
        $currentDate = $startDate->copy()->addDays($day - 1);
        return [
            'month' => $currentDate->month,
            'day' => $currentDate->day,
            'formatted' => $currentDate->month . '月' . $currentDate->day . '日',
        ];
    }

    public function getAvailableActions(User $user, GameSave $save): array
    {
        $dayOfWeek = $this->calculateDayOfWeek($save->current_day);
        $isWeekend = in_array($dayOfWeek, ['saturday', 'sunday']);
        $actions = [];

        switch ($save->phase) {
            case 'morning':
                if ($isWeekend) {
                    $actions = ['study', 'rest', 'exercise', 'go_out'];
                } else {
                    // Weekday morning - auto advance to school
                    $actions = ['go_to_school'];
                }
                break;
            case 'afternoon':
                if ($isWeekend) {
                    $actions = ['study', 'rest', 'exercise', 'go_out'];
                } else {
                    $actions = ['attend_class'];
                }
                break;
            case 'after_school':
                $actions = ['study', 'rest', 'exercise', 'go_out'];
                break;
            case 'night':
                $actions = ['sleep'];
                break;
        }

        // Force rest if HP is critical
        if ($user->hp <= 10 && !in_array('sleep', $actions)) {
            $actions = array_filter($actions, fn($a) => $a !== 'exercise');
            if (!in_array('rest', $actions)) {
                array_unshift($actions, 'rest');
            }
        }

        return $actions;
    }

    public function processStudyAction(User $user, Character $char): array
    {
        $hpCost = (int) AdminSetting::get('hp_cost_study', 10);

        if ($user->hp < $hpCost) {
            return ['success' => false, 'message' => 'HPが足りません！休憩が必要です。'];
        }

        $question = Question::where('character_id', $char->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->first();

        if (!$question) {
            return ['success' => false, 'message' => 'このキャラクターの問題がまだありません。'];
        }

        $user->decrement('hp', $hpCost);
        $user->save();

        $this->logAction($user, 'study', null, -$hpCost, []);

        return [
            'success' => true,
            'question' => $question,
            'character' => $char,
            'hp_cost' => $hpCost,
        ];
    }

    public function processRestAction(User $user): array
    {
        $hpGain = (int) AdminSetting::get('hp_recovery_rest', 30);
        $maxHp = $user->max_hp;

        $actualGain = min($hpGain, $maxHp - $user->hp);
        $user->increment('hp', $actualGain);
        $user->save();

        $this->logAction($user, 'rest', null, $actualGain, []);

        return [
            'success' => true,
            'message' => "休憩した。HP +{$actualGain}",
            'hp_gained' => $actualGain,
        ];
    }

    public function processExerciseAction(User $user): array
    {
        $hpCost = (int) AdminSetting::get('hp_cost_exercise', 15);

        if ($user->hp < $hpCost) {
            return ['success' => false, 'message' => 'HPが足りません！'];
        }

        $user->decrement('hp', $hpCost);
        $user->increment('academic_power', 2);
        $user->increment('frontend_power', 1);
        $user->increment('backend_power', 1);
        $user->save();

        $this->logAction($user, 'exercise', null, -$hpCost, [
            'academic_power' => 2, 'frontend_power' => 1, 'backend_power' => 1,
        ]);

        return [
            'success' => true,
            'message' => "運動した！ HP -{$hpCost} / パワーアップ！",
            'hp_cost' => $hpCost,
        ];
    }

    public function advancePhase(User $user, GameSave $save): array
    {
        $dayOfWeek = $this->calculateDayOfWeek($save->current_day);
        $isWeekend = in_array($dayOfWeek, ['saturday', 'sunday']);

        $phaseOrder = $isWeekend
            ? ['morning', 'afternoon', 'night']
            : ['morning', 'after_school', 'night'];

        $currentIndex = array_search($save->phase, $phaseOrder);
        $nextIndex = $currentIndex + 1;

        if ($nextIndex >= count($phaseOrder)) {
            // Advance to next day
            $save->current_day++;
            $save->phase = $phaseOrder[0];

            // Night/sleep HP recovery
            $hpGain = (int) AdminSetting::get('hp_recovery_sleep', 50);
            $maxHp = $user->max_hp;
            $actualGain = min($hpGain, $maxHp - $user->hp);
            $user->increment('hp', $actualGain);
        } else {
            $save->phase = $phaseOrder[$nextIndex];
            // Auto night (sleep) processing
            if ($save->phase === 'night') {
                return $this->processSleep($user, $save);
            }
        }

        $newDayOfWeek = $this->calculateDayOfWeek($save->current_day);
        $save->day_of_week = $newDayOfWeek;
        $save->is_weekend = in_array($newDayOfWeek, ['saturday', 'sunday']);
        $save->save();

        return ['success' => true, 'save' => $save];
    }

    public function processSleep(User $user, GameSave $save): array
    {
        $hpGain = (int) AdminSetting::get('hp_recovery_sleep', 50);
        $maxHp = $user->max_hp;
        $actualGain = min($hpGain, $maxHp - $user->hp);
        $user->increment('hp', $actualGain);

        // Auto advance to next day morning
        $save->current_day++;
        $dayOfWeek = $this->calculateDayOfWeek($save->current_day);
        $save->day_of_week = $dayOfWeek;
        $save->is_weekend = in_array($dayOfWeek, ['saturday', 'sunday']);
        $save->phase = 'morning';
        $save->save();

        return ['success' => true, 'save' => $save, 'hp_gained' => $actualGain];
    }

    public function checkEndGame(GameSave $save): bool
    {
        return $save->current_day > self::TOTAL_DAYS;
    }

    private function logAction(User $user, string $actionType, ?string $locationSlug, int $hpChange, array $powerChanges): void
    {
        $save = $user->gameSave;
        DailyLog::create([
            'user_id' => $user->id,
            'day' => $save ? $save->current_day : 0,
            'phase' => $save ? $save->phase : 'unknown',
            'action_type' => $actionType,
            'location_slug' => $locationSlug,
            'hp_change' => $hpChange,
            'power_changes' => $powerChanges,
        ]);
    }
}
