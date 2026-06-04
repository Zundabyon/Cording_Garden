<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Affection;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Services\DialogueService;
use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function __construct(private GameService $gameService)
    {
    }

    public function show(int $id)
    {
        $question = Question::with('character')->findOrFail($id);
        return view('game.question', compact('question'));
    }

    public function answer(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer'      => 'required|string',
        ]);

        $user     = Auth::user();
        $save     = $user->gameSave;
        $question = Question::with('character')->findOrFail($request->question_id);
        $selectedAnswer = $request->answer;
        $isCorrect = trim($selectedAnswer) === trim($question->correct_answer);

        // ── 好感度・ステータス更新 ──────────────────
        $affectionGained = 0;
        if ($isCorrect) {
            $baseGain = match($question->difficulty) {
                1 => 1, 2 => 2, 3 => 3, default => 1,
            };
            $maxAffection = (int) AdminSetting::get('max_affection_per_character', 100);

            $affection = Affection::firstOrCreate(
                ['user_id' => $user->id, 'character_id' => $question->character_id],
                ['level' => 0]
            );
            $affectionGained = min($baseGain, $maxAffection - $affection->level);
            $affection->increment('level', $affectionGained);

            $this->increasePowerStat($user, $question->character->subject);
        }

        QuestionAnswer::create([
            'user_id'         => $user->id,
            'question_id'     => $question->id,
            'selected_answer' => $selectedAnswer,
            'is_correct'      => $isCorrect,
            'affection_gained'=> $affectionGained,
            'answered_at'     => now(),
        ]);

        // ── 5問カウント ────────────────────────────
        $character = $question->character;
        $affection = Affection::where('user_id', $user->id)
            ->where('character_id', $question->character_id)->first();
        $currentLevel = $affection?->level ?? 0;

        $dialogue = $isCorrect
            ? $character->getCorrectDialogue($currentLevel)
            : $character->getWrongDialogue($currentLevel);

        // セーブデータのカウントを増やす
        if ($save) {
            $save->increment('daily_study_count');
            $save->refresh();
        }

        $studyLimit = (int) AdminSetting::get('daily_study_limit', 5);
        $isSessionEnd = $save && $save->daily_study_count >= $studyLimit;

        if ($isSessionEnd) {
            // カウントリセット・フェーズ進行
            $save->update(['daily_study_count' => 0, 'current_study_character_id' => null]);
            $this->gameService->advancePhase($user, $save);

            $closingDialogue = DialogueService::getClosing($character->slug, $currentLevel);

            return view('game.study-complete', [
                'character'       => $character,
                'affection'       => $affection,
                'affection_level' => (int) floor($currentLevel / 20),
                'closing'         => $closingDialogue,
                'last_question'   => $question,
                'last_answer'     => $selectedAnswer,
                'last_correct'    => $isCorrect,
                'affection_gained'=> $affectionGained,
                'dialogue'        => $dialogue,
                'study_limit'     => $studyLimit,
            ]);
        }

        return view('game.answer-result', [
            'question'        => $question,
            'selected_answer' => $selectedAnswer,
            'is_correct'      => $isCorrect,
            'affection_gained'=> $affectionGained,
            'character'       => $character,
            'affection'       => $affection,
            'affection_level' => (int) floor($currentLevel / 20),
            'dialogue'        => $dialogue,
            'study_count'     => $save?->daily_study_count ?? 0,
            'study_limit'     => $studyLimit,
        ]);
    }

    private function increasePowerStat(object $user, string $subject): void
    {
        match(true) {
            in_array($subject, ['php', 'laravel']) => ($user->increment('backend_power', 3) && $user->increment('academic_power', 1)),
            in_array($subject, ['html', 'css', 'js', 'typescript', 'vue']) => ($user->increment('frontend_power', 3) && $user->increment('academic_power', 1)),
            default => $user->increment('academic_power', 2),
        };
    }
}
