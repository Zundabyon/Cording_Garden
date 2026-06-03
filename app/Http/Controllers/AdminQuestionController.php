<?php

namespace App\Http\Controllers;

use App\Models\Character;
use App\Models\Question;
use Illuminate\Http\Request;

class AdminQuestionController extends Controller
{
    public function index()
    {
        $questions = Question::with('character')->latest()->paginate(20);
        return view('admin.questions.index', compact('questions'));
    }

    public function create()
    {
        $characters = Character::orderBy('sort_order')->get();
        return view('admin.questions.create', compact('characters'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'category' => 'required|in:multiple_choice,fill_blank,code_complete',
            'difficulty' => 'required|integer|between:1,3',
            'question_text' => 'required|string',
            'code_snippet' => 'nullable|string',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string',
            'explanation' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Question::create($data);

        return redirect()->route('admin.questions.index')->with('success', '問題を追加しました。');
    }

    public function edit(Question $question)
    {
        $characters = Character::orderBy('sort_order')->get();
        return view('admin.questions.edit', compact('question', 'characters'));
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'character_id' => 'required|exists:characters,id',
            'category' => 'required|in:multiple_choice,fill_blank,code_complete',
            'difficulty' => 'required|integer|between:1,3',
            'question_text' => 'required|string',
            'code_snippet' => 'nullable|string',
            'options' => 'required|array|min:2',
            'correct_answer' => 'required|string',
            'explanation' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $question->update($data);

        return redirect()->route('admin.questions.index')->with('success', '問題を更新しました。');
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('admin.questions.index')->with('success', '問題を削除しました。');
    }
}
