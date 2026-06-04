<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Location;
use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActionController extends Controller
{
    public function __construct(private GameService $gameService)
    {
    }

    public function perform(Request $request)
    {
        $user = Auth::user();
        $action = $request->input('action');
        $save = $user->gameSave;

        if (!$save) {
            return redirect()->route('game.index');
        }

        switch ($action) {
            case 'study':
                $characterSlug = $request->input('character_slug', 'php');
                $character = Character::where('slug', $characterSlug)->first();
                if (!$character) {
                    $character = Character::first();
                }
                $result = $this->gameService->processStudyAction($user, $character);
                if (!$result['success']) {
                    return redirect()->route('game.index')->with('error', $result['message']);
                }
                $affection = \App\Models\Affection::firstOrCreate(
                    ['user_id' => $user->id, 'character_id' => $character->id],
                    ['level' => 0]
                );
                $affectionLevel = (int) floor($affection->level / 20);
                $greeting = $character->getGreetingDialogue($affection->level);
                // 勉強開始時にキャラIDを記録
                if ($save) {
                    $save->update(['current_study_character_id' => $character->id]);
                }
                return view('game.action-study', [
                    'question'            => $result['question'],
                    'character'           => $result['character'],
                    'hp_cost'             => $result['hp_cost'],
                    'affection'           => $affection,
                    'affection_level'     => $affectionLevel,
                    'greeting'            => $greeting,
                    'save_study_count'    => $save?->daily_study_count ?? 0,
                ]);

            case 'rest':
                $result = $this->gameService->processRestAction($user);
                return redirect()->route('game.index')->with('success', $result['message']);

            case 'exercise':
                $result = $this->gameService->processExerciseAction($user);
                if (!$result['success']) {
                    return redirect()->route('game.index')->with('error', $result['message']);
                }
                return redirect()->route('game.index')->with('success', $result['message']);

            case 'go_out':
                return $this->goOut($request);

            case 'go_to_school':
            case 'attend_class':
                // Auto-advance phase
                $this->gameService->advancePhase($user, $save);
                return redirect()->route('game.index')->with('success', '学校に行きました。');

            case 'sleep':
                $result = $this->gameService->processSleep($user, $save);
                return redirect()->route('game.index')->with('success', 'おやすみなさい。HP +' . $result['hp_gained']);

            default:
                return redirect()->route('game.index')->with('error', '無効なアクションです。');
        }
    }

    private function goOut(Request $request)
    {
        $user = Auth::user();
        $save = $user->gameSave;

        $dayOfWeek = $this->gameService->calculateDayOfWeek($save->current_day);
        $isWeekend = in_array($dayOfWeek, ['saturday', 'sunday']);

        $context = match(true) {
            $isWeekend && $save->phase === 'morning' => 'weekend_morning',
            $isWeekend && $save->phase === 'afternoon' => 'weekend_afternoon',
            default => 'weekday_after',
        };

        $locations = Location::all()->filter(fn($l) => $l->isAvailableFor($context));

        return view('game.action-location', compact('locations', 'context', 'save'));
    }
}
