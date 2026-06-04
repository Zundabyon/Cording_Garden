@extends('layouts.game')

@section('title', '回答結果')

@section('content')
<div class="max-w-xl mx-auto">

    {{-- ノベル風セリフボックス --}}
    <div class="glass-panel rounded-xl p-5 mb-5 border {{ $is_correct ? 'border-green-500/20' : 'border-red-500/20' }}">
        <div class="flex items-end gap-4">
            {{-- キャラアイコン --}}
            <div class="flex-shrink-0 text-center">
                <div class="text-6xl mb-1 drop-shadow-lg"
                     style="filter: drop-shadow(0 0 12px {{ $is_correct ? 'rgba(74,222,128,0.5)' : 'rgba(248,113,113,0.4)' }})">
                    {{ $character->getEmojiIcon() }}
                </div>
                <div class="text-xs text-purple-300 font-medium">{{ $character->name }}</div>
            </div>

            <div class="flex-1">
                {{-- 正解/不正解バナー --}}
                <div class="flex items-center gap-2 mb-2">
                    @if($is_correct)
                        <span class="text-green-400 font-bold text-sm">⭕ 正解！</span>
                        @if($affection_gained > 0)
                        <span class="text-xs text-pink-400 glass-panel px-2 py-0.5 rounded-full">💕 好感度 +{{ $affection_gained }}</span>
                        @endif
                    @else
                        <span class="text-red-400 font-bold text-sm">❌ 不正解…</span>
                    @endif
                    <div class="ml-auto flex gap-px">
                        @for($i = 0; $i < 5; $i++)
                        <span class="text-xs {{ $i < $affection_level + 1 ? 'text-pink-400' : 'text-gray-600' }}">♥</span>
                        @endfor
                    </div>
                </div>

                {{-- キャラセリフ（タイプライター） --}}
                <div class="bg-black/40 border {{ $is_correct ? 'border-green-500/30' : 'border-red-500/20' }} rounded-xl p-4 relative">
                    <div class="absolute top-2 left-3 w-2 h-2 rounded-full {{ $is_correct ? 'bg-green-400' : 'bg-red-400' }} animate-pulse"></div>
                    <p class="text-white text-sm leading-relaxed pl-4"
                       x-data="typewriter('{{ addslashes($dialogue['text']) }}')"
                       x-init="start()"
                       x-text="displayed">
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- 解答詳細 --}}
    <div class="glass-panel rounded-xl p-5 mb-5 space-y-4">
        @if(!$is_correct)
        <div class="flex items-start gap-3">
            <span class="text-xs text-gray-500 mt-0.5 w-20 flex-shrink-0">あなたの回答</span>
            <span class="text-red-400 text-sm font-medium">{{ $selected_answer }}</span>
        </div>
        <div class="flex items-start gap-3">
            <span class="text-xs text-gray-500 mt-0.5 w-20 flex-shrink-0">正解</span>
            <span class="text-green-400 text-sm font-medium">{{ $question->correct_answer }}</span>
        </div>
        <hr class="border-white/10">
        @endif

        <div>
            <p class="text-xs text-purple-400 mb-1">💡 解説</p>
            <p class="text-sm text-gray-300 leading-relaxed">{{ $question->explanation }}</p>
        </div>
    </div>

    {{-- 好感度UP演出 --}}
    @if($is_correct && $affection_gained > 0)
    <div class="text-center mb-4 text-pink-400 text-sm animate-bounce">
        ♥ {{ $character->name }}との仲が深まった ♥
    </div>
    @endif

    {{-- 次のアクション --}}
    <div class="flex gap-3 justify-center">
        <a href="{{ route('game.index') }}" class="btn-secondary px-6 py-3 text-sm">
            🎮 ゲームに戻る
        </a>
        <form method="POST" action="{{ route('game.action') }}">
            @csrf
            <input type="hidden" name="action" value="study">
            <input type="hidden" name="character_slug" value="{{ $character->slug }}">
            <button type="submit" class="btn-primary px-6 py-3 text-sm">
                📚 もう一問
            </button>
        </form>
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
            }, 45);
        }
    }));
});
</script>
@endsection
