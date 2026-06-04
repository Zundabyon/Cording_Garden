@extends('layouts.game')

@section('title', '勉強終了 - ' . $character->name)

@section('content')
<div class="max-w-xl mx-auto">

    {{-- 最後の1問の結果（小さく表示） --}}
    <div class="glass-panel rounded-xl p-4 mb-4 flex items-center gap-3
        {{ $last_correct ? 'border border-green-500/20' : 'border border-red-500/20' }}">
        <span class="text-2xl">{{ $last_correct ? '⭕' : '❌' }}</span>
        <div class="flex-1 text-sm text-gray-400">
            最後の問題：
            <span class="{{ $last_correct ? 'text-green-400' : 'text-red-400' }}">
                {{ $last_correct ? '正解' : '不正解' }}
            </span>
            @if($last_correct && $affection_gained > 0)
            　<span class="text-pink-400">💕 好感度 +{{ $affection_gained }}</span>
            @endif
        </div>
        <div class="text-xs text-purple-400">{{ $last_question->correct_answer }}</div>
    </div>

    {{-- 5問完了バナー --}}
    <div class="text-center mb-5">
        <div class="text-4xl mb-2">📚✨</div>
        <h2 class="text-xl font-bold text-white mb-1">今日の勉強が終わりました！</h2>
        <div class="flex justify-center gap-2">
            @for($i = 1; $i <= $study_limit; $i++)
            <div class="w-4 h-4 rounded-full bg-purple-400 animate-pulse" style="animation-delay: {{ ($i-1) * 0.1 }}s"></div>
            @endfor
        </div>
        <p class="text-gray-400 text-sm mt-2">{{ $study_limit }}問クリア</p>
    </div>

    {{-- 締めセリフボックス --}}
    <div class="glass-panel rounded-xl p-5 mb-5 border border-purple-500/30">
        <div class="flex items-end gap-4">
            <div class="flex-shrink-0 text-center">
                <div class="text-6xl mb-1 drop-shadow-lg"
                     style="filter: drop-shadow(0 0 16px rgba(168,85,247,0.6))">
                    {{ $character->getEmojiIcon() }}
                </div>
                <div class="text-xs text-purple-300 font-medium">{{ $character->name }}</div>
            </div>

            <div class="flex-1">
                <div class="flex items-center gap-2 mb-2">
                    <div class="flex gap-px">
                        @for($i = 0; $i < 5; $i++)
                        <span class="text-sm {{ $i < $affection_level + 1 ? 'text-pink-400' : 'text-gray-600' }}">♥</span>
                        @endfor
                    </div>
                    <span class="text-xs text-gray-500">{{ $affection?->getRelationshipLabel() ?? '初対面' }}</span>
                    @if($affection)
                    <span class="text-xs text-purple-400 ml-auto">{{ $affection->level }}/100</span>
                    @endif
                </div>

                {{-- 締めセリフ（タイプライター） --}}
                <div class="bg-black/40 border border-purple-500/40 rounded-xl p-4 relative">
                    <div class="absolute top-2 left-3 w-2 h-2 rounded-full bg-pink-400 animate-pulse"></div>
                    <p class="text-white text-sm leading-relaxed pl-4"
                       x-data="typewriter('{{ addslashes($closing['text']) }}')"
                       x-init="start()"
                       x-text="displayed">
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- 次の日へ --}}
    <div class="text-center">
        <p class="text-gray-500 text-xs mb-4">フェーズが進みました</p>
        <a href="{{ route('game.index') }}" class="btn-primary px-8 py-3 inline-block text-sm">
            🌙 次へ進む
        </a>
    </div>
</div>

<script>
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
            }, 50);
        }
    }));
});
</script>
@endsection
