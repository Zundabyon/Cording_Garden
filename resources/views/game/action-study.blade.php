@extends('layouts.game')

@section('title', '勉強 - ' . $character->name)

@section('content')
<div class="max-w-2xl mx-auto" x-data="{ answered: false, selectedAnswer: null }">

    {{-- ノベル風セリフボックス --}}
    <div class="glass-panel rounded-xl p-5 mb-5 border border-white/10"
         x-data="{ show: false }" x-init="setTimeout(() => show = true, 100)">
        <div class="flex items-end gap-4">
            {{-- キャラアイコン --}}
            <div class="flex-shrink-0 text-center">
                <div class="text-6xl mb-1 drop-shadow-lg" style="filter: drop-shadow(0 0 12px rgba(168,85,247,0.5))">
                    {{ $character->getEmojiIcon() }}
                </div>
                <div class="text-xs text-purple-300 font-medium">{{ $character->name }}</div>
                <div class="text-xs text-gray-500">{{ $character->name_kana }}</div>
            </div>

            {{-- セリフ --}}
            <div class="flex-1">
                {{-- 好感度バッジ --}}
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex gap-px">
                        @for($i = 0; $i < 5; $i++)
                        <span class="text-sm {{ $i < $affection_level + 1 ? 'text-pink-400' : 'text-gray-600' }}">♥</span>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500">{{ $affection ? $affection->getRelationshipLabel() : '初対面' }}</span>
                    <div class="ml-auto flex items-center gap-3">
                        {{-- 問題カウンター --}}
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                            <div class="w-3 h-3 rounded-full {{ $i <= ($save_study_count ?? 0) ? 'bg-purple-400' : 'bg-gray-700' }}"></div>
                            @endfor
                        </div>
                        <span class="text-xs text-gray-500">{{ ($save_study_count ?? 0) + 1 }}/5問</span>
                        <span class="text-xs text-red-400">HP -{{ $hp_cost }}</span>
                    </div>
                </div>

                {{-- セリフ本文（タイプライター風） --}}
                <div class="bg-black/40 border border-purple-500/30 rounded-xl p-4 relative">
                    <div class="absolute top-2 left-3 w-2 h-2 rounded-full bg-purple-400 animate-pulse"></div>
                    <p class="text-white text-sm leading-relaxed pl-4 typewriter"
                       x-data="typewriter('{{ addslashes($greeting['text']) }}')"
                       x-init="start()"
                       x-text="displayed">
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- 問題カード --}}
    <div class="glass-panel rounded-xl p-6 mb-5">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs px-2 py-1 rounded-full {{ $question->difficulty === 1 ? 'bg-green-900/50 text-green-400' : ($question->difficulty === 2 ? 'bg-yellow-900/50 text-yellow-400' : 'bg-red-900/50 text-red-400') }}">
                {{ $question->getDifficultyLabel() }}
            </span>
            <span class="text-xs text-gray-500 glass-panel px-3 py-1 rounded-full">
                {{ $question->category === 'multiple_choice' ? '選択問題' : '穴埋め問題' }}
            </span>
        </div>

        <p class="text-white font-medium mb-4 text-base leading-relaxed">{{ $question->question_text }}</p>

        @if($question->code_snippet)
        <pre class="bg-black/50 rounded-lg p-4 text-sm text-green-300 font-mono overflow-x-auto mb-4 border border-green-900/30">{{ $question->code_snippet }}</pre>
        @endif

        <form method="POST" action="{{ route('question.answer') }}" id="answerForm">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->id }}">
            <input type="hidden" name="answer" id="selectedAnswerInput" value="">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($question->options as $index => $option)
                <button type="button"
                        class="option-btn text-left glass-panel rounded-xl p-4 text-sm hover:bg-white/15 transition-all border border-transparent hover:border-purple-400 hover:shadow-lg hover:shadow-purple-900/20"
                        onclick="selectAnswer(this, '{{ addslashes($option) }}')">
                    <span class="inline-block w-6 h-6 rounded-full bg-purple-900/50 text-purple-300 text-xs font-bold text-center leading-6 mr-2">{{ ['A','B','C','D'][$index] }}</span>
                    {{ $option }}
                </button>
                @endforeach
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a href="{{ route('game.index') }}" class="text-xs text-gray-600 hover:text-gray-400 transition">
                    ← スキップ
                </a>
                <button type="submit" id="submitBtn" disabled
                        class="btn-primary px-8 py-3 opacity-40 cursor-not-allowed transition-all text-sm">
                    回答する ✓
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function selectAnswer(btn, answer) {
    document.querySelectorAll('.option-btn').forEach(b => {
        b.classList.remove('border-purple-400', 'bg-purple-900/30', 'shadow-lg');
    });
    btn.classList.add('border-purple-400', 'bg-purple-900/30', 'shadow-lg');
    document.getElementById('selectedAnswerInput').value = answer;
    const sb = document.getElementById('submitBtn');
    sb.disabled = false;
    sb.classList.remove('opacity-40', 'cursor-not-allowed');
}

// タイプライター効果
document.addEventListener('alpine:init', () => {
    Alpine.data('typewriter', (text) => ({
        displayed: '',
        start() {
            let i = 0;
            const interval = setInterval(() => {
                if (i < text.length) {
                    this.displayed += text[i++];
                } else {
                    clearInterval(interval);
                }
            }, 40);
        }
    }));
});
</script>
@endsection
