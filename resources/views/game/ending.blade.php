@extends('layouts.game')

@section('title', 'エンディング')

@section('content')
@php $user = auth()->user(); @endphp
<div class="max-w-xl mx-auto text-center py-8">

    <div class="glass-panel rounded-2xl p-10 mb-6">
        <div class="text-7xl mb-6 animate-bounce">🎊</div>
        <h1 class="text-3xl font-bold text-purple-300 mb-2">ゲームクリア！</h1>
        <p class="text-gray-400 mb-6">90日間のコーディング学習が終わりました</p>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="glass-panel rounded-lg p-4">
                <div class="text-2xl font-bold text-blue-400">{{ $user->academic_power }}</div>
                <div class="text-xs text-gray-400">学術力</div>
            </div>
            <div class="glass-panel rounded-lg p-4">
                <div class="text-2xl font-bold text-cyan-400">{{ $user->frontend_power }}</div>
                <div class="text-xs text-gray-400">フロント</div>
            </div>
            <div class="glass-panel rounded-lg p-4">
                <div class="text-2xl font-bold text-green-400">{{ $user->backend_power }}</div>
                <div class="text-xs text-gray-400">バックエンド</div>
            </div>
        </div>

        <div class="space-y-3">
            @foreach($user->affections()->with('character')->orderByDesc('level')->take(3)->get() as $aff)
            <div class="flex items-center justify-between glass-panel rounded-lg p-3">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">{{ $aff->character->getEmojiIcon() }}</span>
                    <span class="text-sm text-white">{{ $aff->character->name }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-pink-400 font-medium">{{ $aff->getRelationshipLabel() }}</span>
                    <span class="text-xs text-gray-400">{{ $aff->level }}pt</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="flex gap-3 justify-center flex-wrap">
        <a href="{{ route('share.x') }}" class="btn-game bg-sky-600 hover:bg-sky-500 text-white px-6 py-3">
            𝕏 Xでシェア
        </a>
        <form method="POST" action="{{ route('game.start') }}">
            @csrf
            <input type="hidden" name="player_name" value="{{ $user->player_name }}">
            <button type="submit" class="btn-primary px-6 py-3">
                🔄 もう一度プレイ
            </button>
        </form>
    </div>
</div>
@endsection
