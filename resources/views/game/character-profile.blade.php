@extends('layouts.game')

@section('title', $character->name)

@section('content')
<div class="max-w-xl mx-auto">

    {{-- Character card --}}
    <div class="glass-panel rounded-2xl overflow-hidden mb-5">

        {{-- Header gradient --}}
        <div class="bg-gradient-to-r {{ $character->getColorClass() }} p-8 text-center">
            <div class="text-7xl mb-3">{{ $character->getEmojiIcon() }}</div>
            <h1 class="text-2xl font-bold text-white">{{ $character->name }}</h1>
            <p class="text-sm text-white/70 mt-1">
                {{ $character->gender === 'female' ? '女性' : ($character->gender === 'male' ? '男性' : '謎') }}
                ・ {{ strtoupper($character->subject) }} キャラ
            </p>
        </div>

        {{-- Details --}}
        <div class="p-6">

            {{-- Affection meter --}}
            @php
                $level = $affection ? $affection->level : 0;
                $label = $affection ? $affection->getRelationshipLabel() : '知り合い';
                $hearts = $affection ? $affection->getHeartCount() : 0;
            @endphp

            <div class="mb-5">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-pink-400">好感度</span>
                    <span class="text-sm font-medium text-pink-300">{{ $label }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-4 overflow-hidden">
                    <div class="bg-gradient-to-r from-pink-500 to-red-400 h-4 rounded-full transition-all duration-700"
                         style="width: {{ $level }}%"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>0</span>
                    <span class="text-pink-400 font-medium">{{ $level }} / 100</span>
                    <span>100</span>
                </div>
                <div class="flex gap-1 mt-2 justify-center">
                    @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $level >= $i * 20 ? 'text-pink-400' : 'text-gray-600' }} text-xl">♥</span>
                    @endfor
                </div>
            </div>

            {{-- Personality --}}
            <div class="glass-panel rounded-lg p-4 mb-4">
                <h3 class="text-xs text-purple-400 mb-2">性格・特徴</h3>
                <p class="text-sm text-gray-300 leading-relaxed">{{ $character->personality }}</p>
            </div>

            {{-- Description --}}
            @php
                $characterProfile = $character->getCharacterProfile();
                $languageDesc = $character->getLanguageDescription();
            @endphp

            @if($characterProfile)
            <div class="glass-panel rounded-lg p-4 mb-4">
                <h3 class="text-xs text-purple-400 mb-2">プロフィール</h3>
                <p class="text-sm text-gray-300 leading-relaxed">{{ $characterProfile }}</p>
            </div>
            @endif

            {{-- Language Description --}}
            @if($languageDesc)
            <div class="glass-panel rounded-lg p-4 mb-4 border-l-4 border-blue-500">
                <h3 class="text-xs text-blue-400 mb-2">📚 {{ strtoupper($character->subject) }} について</h3>
                <p class="text-sm text-gray-300 leading-relaxed">{{ $languageDesc }}</p>
            </div>
            @endif

            {{-- Locked note --}}
            @if(!$character->is_unlocked)
            <div class="glass-panel bg-gray-800/50 rounded-lg p-4 text-center">
                <p class="text-gray-500 text-sm">🔒 このキャラクターはまだ解放されていません</p>
            </div>
            @endif
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="{{ route('characters.index') }}" class="text-sm text-gray-500 hover:text-gray-400 transition">
            ← キャラクター一覧へ
        </a>
    </div>
</div>
@endsection
