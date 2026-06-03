@extends('layouts.game')

@section('title', 'キャラクター一覧')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-purple-300">キャラクター一覧</h1>
    <p class="text-sm text-gray-400 mt-1">これまでに出会ったプログラミング言語の仲間たち</p>
</div>

<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
    @foreach($characters as $character)
    @php $affectionLevel = $affections[$character->id] ?? 0; @endphp

    <a href="{{ route('characters.show', $character->slug) }}"
       class="glass-panel rounded-xl p-5 text-center hover:bg-white/10 transition group
       {{ !$character->is_unlocked ? 'opacity-50' : '' }}">

        {{-- Character icon with gradient --}}
        <div class="w-16 h-16 mx-auto rounded-full bg-gradient-to-br {{ $character->getColorClass() }} flex items-center justify-center text-3xl mb-3 group-hover:scale-110 transition">
            {{ $character->getEmojiIcon() }}
        </div>

        <h3 class="font-bold text-white text-sm mb-1">{{ $character->name }}</h3>

        @if($character->is_unlocked)
        <div class="flex justify-center gap-px mb-2">
            @for($i = 1; $i <= 5; $i++)
            <span class="{{ $affectionLevel >= $i * 20 ? 'text-pink-400' : 'text-gray-600' }} text-sm">♥</span>
            @endfor
        </div>
        <p class="text-xs text-gray-400 leading-relaxed line-clamp-2">{{ Str::limit($character->personality, 40) }}</p>
        @else
        <div class="text-gray-500 text-sm">
            🔒 まだ解放されていない
        </div>
        @endif
    </a>
    @endforeach
</div>

<div class="mt-6 text-center">
    <a href="{{ route('game.index') }}" class="text-sm text-gray-500 hover:text-gray-400 transition">
        ← ゲームに戻る
    </a>
</div>
@endsection
