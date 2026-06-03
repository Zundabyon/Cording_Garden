@extends('layouts.game')

@section('title', '回答結果')

@section('content')
<div class="max-w-xl mx-auto">

    {{-- Result card --}}
    <div class="glass-panel rounded-xl p-8 text-center mb-5
        {{ $is_correct ? 'border border-green-500/30 bg-green-900/10' : 'border border-red-500/30 bg-red-900/10' }}">

        {{-- Big result icon --}}
        <div class="text-7xl mb-4 animate-bounce">
            {{ $is_correct ? '⭕' : '❌' }}
        </div>

        <h2 class="text-2xl font-bold mb-2 {{ $is_correct ? 'text-green-400' : 'text-red-400' }}">
            {{ $is_correct ? '正解！' : '不正解...' }}
        </h2>

        {{-- Character reaction --}}
        <div class="flex items-center justify-center gap-3 mb-4">
            <span class="text-3xl">{{ $character->getEmojiIcon() }}</span>
            <p class="text-sm text-gray-300 italic">
                @if($is_correct)
                    "{{ ['すごい！正解です！', 'よくできました！', '完璧な回答ね！', 'さすがです！'][ array_rand(['a','b','c','d']) ] }}"
                @else
                    "{{ ['惜しい！もう一度確認しよう', '次は頑張って！', '難しかったね...', 'ドンマイ！'][ array_rand(['a','b','c','d']) ] }}"
                @endif
            </p>
        </div>

        @if($is_correct && $affection_gained > 0)
        <div class="glass-panel bg-pink-900/20 border-pink-500/30 rounded-lg p-3 mb-4">
            <p class="text-pink-400 font-medium">💕 {{ $character->name }}の好感度 +{{ $affection_gained }}</p>
        </div>
        @endif

        {{-- Correct answer reveal --}}
        @if(!$is_correct)
        <div class="glass-panel rounded-lg p-4 mb-4 text-left">
            <p class="text-xs text-gray-400 mb-1">正解は：</p>
            <p class="text-green-400 font-medium">{{ $question->correct_answer }}</p>
            <p class="text-xs text-gray-500 mt-1">あなたの回答：<span class="text-red-400">{{ $selected_answer }}</span></p>
        </div>
        @endif

        {{-- Explanation --}}
        <div class="glass-panel rounded-lg p-4 text-left">
            <p class="text-xs text-purple-400 mb-1">💡 解説</p>
            <p class="text-sm text-gray-300 leading-relaxed">{{ $question->explanation }}</p>
        </div>
    </div>

    {{-- Next actions --}}
    <div class="flex gap-3 justify-center">
        <a href="{{ route('game.index') }}" class="btn-primary px-6 py-3">
            🎮 ゲームに戻る
        </a>
        <form method="POST" action="{{ route('game.action') }}">
            @csrf
            <input type="hidden" name="action" value="study">
            <input type="hidden" name="character_slug" value="{{ $character->slug }}">
            <button type="submit" class="btn-secondary px-6 py-3">
                📚 もう一問
            </button>
        </form>
    </div>
</div>
@endsection
