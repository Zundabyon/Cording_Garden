<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Affection;
use App\Models\Question;
use App\Models\QuestionAnswer;
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
            'answer' => 'required|string',
        ]);

        $user = Auth::user();
        $question = Question::with('character')->findOrFail($request->question_id);
        $selectedAnswer = $request->answer;
        $isCorrect = trim($selectedAnswer) === trim($question->correct_answer);

        $affectionGained = 0;
        if ($isCorrect) {
            // 難易度に応じた好感度上昇（最大3）
            $baseGain = match($question->difficulty) {
                1 => 1,
                2 => 2,
                3 => 3,
                default => 1,
            };
            $maxAffection = (int) AdminSetting::get('max_affection_per_character', 100);

            $affection = Affection::firstOrCreate(
                ['user_id' => $user->id, 'character_id' => $question->character_id],
                ['level' => 0]
            );

            $affectionGained = min($baseGain, $maxAffection - $affection->level);
            $affection->increment('level', $affectionGained);

            // Increase power stats based on character subject
            $this->increasePowerStat($user, $question->character->subject);
        }

        QuestionAnswer::create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'selected_answer' => $selectedAnswer,
            'is_correct' => $isCorrect,
            'affection_gained' => $affectionGained,
            'answered_at' => now(),
        ]);

        $affection = Affection::where('user_id', $user->id)
            ->where('character_id', $question->character_id)
            ->first();
        $affectionLevel = (int) floor(($affection?->level ?? 0) / 20);
        $character = $question->character;
        $currentLevel = $affection?->level ?? 0;
        $dialogue = $isCorrect
            ? $character->getCorrectDialogue($currentLevel)
            : $character->getWrongDialogue($currentLevel);

        return view('game.answer-result', [
            'question' => $question,
            'selected_answer' => $selectedAnswer,
            'is_correct' => $isCorrect,
            'affection_gained' => $affectionGained,
            'character' => $character,
            'affection' => $affection,
            'affection_level' => $affectionLevel,
            'dialogue' => $dialogue,
        ]);
    }

    private function increasePowerStat(object $user, string $subject): void
    {
        switch ($subject) {
            case 'php':
            case 'laravel':
                $user->increment('backend_power', 3);
                $user->increment('academic_power', 1);
                break;
            case 'html':
            case 'css':
            case 'js':
            case 'typescript':
            case 'vue':
                $user->increment('frontend_power', 3);
                $user->increment('academic_power', 1);
                break;
            default:
                $user->increment('academic_power', 2);
        }
    }
}
