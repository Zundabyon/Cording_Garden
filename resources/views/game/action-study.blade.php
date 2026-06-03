@extends('layouts.game')

@section('title', '勉強 - ' . $character->name)

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ answered: false, selectedAnswer: null, isCorrect: null }">

    {{-- Character header --}}
    <div class="glass-panel rounded-xl p-5 mb-5 flex items-center gap-4">
        <div class="text-5xl">{{ $character->getEmojiIcon() }}</div>
        <div>
            <h2 class="text-lg font-bold text-white">{{ $character->name }}と勉強中</h2>
            <p class="text-sm text-gray-400">{{ $character->personality }}</p>
        </div>
        <div class="ml-auto text-sm text-red-400">
            HP -{{ $hp_cost }}
        </div>
    </div>

    {{-- Question card --}}
    <div class="glass-panel rounded-xl p-6 mb-5">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs text-purple-400">{{ $question->getDifficultyLabel() }}</span>
            <span class="text-xs text-gray-500 glass-panel px-3 py-1 rounded-full">
                {{ $question->category === 'multiple_choice' ? '選択問題' : ($question->category === 'fill_blank' ? '穴埋め問題' : 'コード完成') }}
            </span>
        </div>

        <p class="text-white font-medium mb-4 text-lg leading-relaxed">{{ $question->question_text }}</p>

        @if($question->code_snippet)
        <pre class="bg-black/40 rounded-lg p-4 text-sm text-green-300 font-mono overflow-x-auto mb-4">{{ $question->code_snippet }}</pre>
        @endif

        <form method="POST" action="{{ route('question.answer') }}" id="answerForm">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="answer" id="selectedAnswerInput" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($question->options as $index => $option)
                <button type="button"
                        class="option-btn text-left glass-panel rounded-lg p-4 text-sm hover:bg-white/15 transition border border-transparent hover:border-purple-400"
                        onclick="selectAnswer(this, '{{ $option }}')">
                    <span class="text-gray-400 font-mono mr-2">{{ ['A', 'B', 'C', 'D'][$index] }}.</span>
                    {{ $option }}
                </button>
                @endforeach
            </div>

            <div class="mt-5 text-center">
                <button type="submit" id="submitBtn" disabled
                        class="btn-primary px-8 py-3 opacity-50 cursor-not-allowed transition-all"
                        id="submitBtn">
                    回答する ✓
                </button>
            </div>
        </form>
    </div>

    <div class="text-center">
        <a href="{{ route('game.index') }}" class="text-xs text-gray-500 hover:text-gray-400 transition">
            ← ゲームに戻る（問題をスキップ）
        </a>
    </div>
</div>

<script>
function selectAnswer(btn, answer) {
    document.querySelectorAll('.option-btn').forEach(b => {
        b.classList.remove('border-purple-400', 'bg-purple-900/30');
    });
    btn.classList.add('border-purple-400', 'bg-purple-900/30');
    document.getElementById('selectedAnswerInput').value = answer;
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = false;
    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
}
</script>
@endsection
