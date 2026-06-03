@extends('layouts.game')

@section('title', 'ゲームメイン')

@section('content')
@php $user = auth()->user(); @endphp

{{-- New Game / No Save --}}
@if(!$state['has_save'])
<div class="text-center py-16" x-data="{ showForm: false }">
    <div class="glass-panel rounded-2xl p-10 max-w-lg mx-auto">
        <div class="text-6xl mb-6">🌸</div>
        <h1 class="text-3xl font-bold text-purple-300 mb-2">Cording Garden</h1>
        <p class="text-gray-400 mb-8 text-sm">コーディングとキャラクターとの出会いの物語</p>

        <div x-show="!showForm">
            <p class="text-gray-300 mb-6 text-sm leading-relaxed">
                3学期が始まった。プログラミングが苦手な君は、<br>
                PHP子先輩たちに出会い、コーディングを学んでいく——
            </p>
            <button @click="showForm = true" class="btn-primary text-lg px-8 py-3">
                🎮 ゲームスタート
            </button>
        </div>

        <div x-show="showForm" x-transition>
            <form method="POST" action="{{ route('game.start') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-400 mb-2">プレイヤー名を入力してください</label>
                    <input type="text" name="player_name" value="{{ old('player_name', $user->name) }}"
                           class="w-full bg-white/10 border border-white/20 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-purple-400"
                           placeholder="あなたの名前" maxlength="20" required>
                    @error('player_name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit" class="btn-primary w-full py-3">
                    ✨ はじめる
                </button>
                <button type="button" @click="showForm = false" class="btn-secondary w-full py-2 text-sm">
                    戻る
                </button>
            </form>
        </div>
    </div>
</div>

@else
{{-- Game is active --}}
@php
    $save = $state['save'];
    $gameDate = $state['game_date'];
@endphp

{{-- Date Header --}}
<div class="glass-panel rounded-xl p-4 mb-6 text-center">
    <div class="flex items-center justify-center gap-4">
        <div class="text-2xl font-bold text-purple-300">
            {{ $gameDate['formatted'] }}（{{ $state['day_of_week_label'] }}）
        </div>
        <div class="glass-panel px-3 py-1 rounded-full text-sm
            {{ $state['phase'] === 'morning' ? 'text-yellow-300' :
               ($state['phase'] === 'afternoon' ? 'text-orange-300' :
               ($state['phase'] === 'after_school' ? 'text-blue-300' : 'text-indigo-300')) }}">
            {{ $state['phase_label'] }}
        </div>
        @if($state['is_weekend'])
        <div class="glass-panel px-3 py-1 rounded-full text-sm text-green-300">
            週末 🎉
        </div>
        @endif
    </div>

    {{-- Progress bar --}}
    <div class="mt-3">
        <div class="flex justify-between text-xs text-gray-500 mb-1">
            <span>1日目</span>
            <span>90日目</span>
        </div>
        <div class="w-full bg-gray-700 rounded-full h-2">
            <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-2 rounded-full transition-all duration-500"
                 style="width: {{ ($state['current_day'] / $state['total_days']) * 100 }}%"></div>
        </div>
    </div>
</div>

{{-- HP Warning --}}
@if($user->hp <= 10)
<div class="glass-panel bg-red-900/30 border-red-500/30 rounded-xl p-4 mb-4 text-center">
    <span class="text-red-400 font-bold">⚠️ HPが危険域です！休憩してください！</span>
</div>
@elseif($user->hp <= 30)
<div class="glass-panel bg-yellow-900/30 border-yellow-500/30 rounded-xl p-4 mb-4 text-center">
    <span class="text-yellow-400">💛 HPが少なくなっています。</span>
</div>
@endif

{{-- Main action area --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    {{-- Character area --}}
    <div class="md:col-span-2">
        <div class="glass-panel rounded-xl p-5">
            <h2 class="text-sm font-semibold text-purple-300 mb-4">キャラクター</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($state['characters'] as $charData)
                @php $char = $charData['character']; $aff = $charData['affection']; @endphp
                <a href="{{ route('characters.show', $char->slug) }}"
                   class="glass-panel rounded-lg p-3 text-center hover:bg-white/10 transition group">
                    <div class="text-3xl mb-1 group-hover:scale-110 transition">{{ $char->getEmojiIcon() }}</div>
                    <div class="text-xs font-medium text-white truncate">{{ $char->name }}</div>
                    <div class="flex justify-center mt-1 gap-px">
                        @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $aff >= $i * 20 ? 'heart-full' : 'heart-empty' }} text-xs">♥</span>
                        @endfor
                    </div>
                    @if(!$char->is_unlocked)
                    <div class="text-xs text-gray-500 mt-1">🔒 未解放</div>
                    @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Stats panel --}}
    <div class="glass-panel rounded-xl p-5">
        <h2 class="text-sm font-semibold text-purple-300 mb-4">ステータス</h2>
        <div class="space-y-3 text-sm">
            <div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="text-red-400">HP</span>
                    <span>{{ $user->hp }}/{{ $user->max_hp }}</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-2">
                    <div class="hp-bar h-2 rounded-full {{ $user->getHpPercentage() > 50 ? 'bg-green-500' : ($user->getHpPercentage() > 25 ? 'bg-yellow-500' : 'bg-red-500') }}"
                         style="width: {{ $user->getHpPercentage() }}%"></div>
                </div>
            </div>
            <div class="flex justify-between">
                <span class="text-blue-400">学術力</span>
                <span class="font-mono">{{ $user->academic_power }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-cyan-400">フロントエンド</span>
                <span class="font-mono">{{ $user->frontend_power }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-green-400">バックエンド</span>
                <span class="font-mono">{{ $user->backend_power }}</span>
            </div>
        </div>
    </div>
</div>

{{-- Actions --}}
<div class="glass-panel rounded-xl p-5">
    <h2 class="text-sm font-semibold text-purple-300 mb-4">行動選択</h2>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach($state['available_actions'] as $action)
        @switch($action)
        @case('study')
        <div x-data="{ showChars: false }" class="col-span-full sm:col-span-1">
            <button @click="showChars = !showChars"
                    class="btn-primary w-full py-4 text-sm {{ $user->hp <= 10 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $user->hp <= 10 ? 'disabled' : '' }}>
                📚 勉強する
                <span class="block text-xs opacity-70 mt-1">HP -10</span>
            </button>
            <div x-show="showChars" x-transition class="mt-2 glass-panel rounded-lg p-3">
                <p class="text-xs text-gray-400 mb-2">誰に教えてもらう？</p>
                <div class="grid grid-cols-2 gap-2">
                    @foreach($state['characters'] as $cd)
                    @if($cd['character']->is_unlocked)
                    <form method="POST" action="{{ route('game.action') }}">
                        @csrf
                        <input type="hidden" name="action" value="study">
                        <input type="hidden" name="character_slug" value="{{ $cd['character']->slug }}">
                        <button type="submit"
                                class="w-full glass-panel hover:bg-white/20 rounded-lg p-2 text-xs transition text-left flex items-center gap-2">
                            <span>{{ $cd['character']->getEmojiIcon() }}</span>
                            <span class="truncate">{{ $cd['character']->name }}</span>
                        </button>
                    </form>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        @break

        @case('rest')
        <form method="POST" action="{{ route('game.action') }}">
            @csrf
            <input type="hidden" name="action" value="rest">
            <button type="submit" class="btn-secondary w-full py-4 text-sm">
                😴 休憩する
                <span class="block text-xs opacity-70 mt-1">HP +30</span>
            </button>
        </form>
        @break

        @case('exercise')
        <form method="POST" action="{{ route('game.action') }}">
            @csrf
            <input type="hidden" name="action" value="exercise">
            <button type="submit" class="btn-success w-full py-4 text-sm {{ $user->hp <= 15 ? 'opacity-50' : '' }}"
                    {{ $user->hp <= 15 ? 'disabled' : '' }}>
                🏃 運動する
                <span class="block text-xs opacity-70 mt-1">HP -15 / パワー↑</span>
            </button>
        </form>
        @break

        @case('go_out')
        <form method="POST" action="{{ route('game.action') }}">
            @csrf
            <input type="hidden" name="action" value="go_out">
            <button type="submit" class="btn-game bg-pink-700 hover:bg-pink-600 text-white w-full py-4 text-sm">
                🚶 外出する
                <span class="block text-xs opacity-70 mt-1">ロケーションへ</span>
            </button>
        </form>
        @break

        @case('go_to_school')
        <form method="POST" action="{{ route('game.action') }}">
            @csrf
            <input type="hidden" name="action" value="go_to_school">
            <button type="submit" class="btn-primary w-full py-4 text-sm col-span-full">
                🏫 登校する
                <span class="block text-xs opacity-70 mt-1">学校へ行く</span>
            </button>
        </form>
        @break

        @case('attend_class')
        <form method="POST" action="{{ route('game.action') }}">
            @csrf
            <input type="hidden" name="action" value="attend_class">
            <button type="submit" class="btn-primary w-full py-4 text-sm col-span-full">
                📝 授業に出席する
                <span class="block text-xs opacity-70 mt-1">午後の授業</span>
            </button>
        </form>
        @break

        @case('sleep')
        <form method="POST" action="{{ route('game.action') }}">
            @csrf
            <input type="hidden" name="action" value="sleep">
            <button type="submit" class="btn-game bg-indigo-700 hover:bg-indigo-600 text-white w-full py-4 text-sm col-span-full">
                🌙 就寝する
                <span class="block text-xs opacity-70 mt-1">HP +50 / 次の日へ</span>
            </button>
        </form>
        @break
        @endswitch
        @endforeach
    </div>

    {{-- Skip phase --}}
    <div class="mt-4 border-t border-white/10 pt-4 text-center">
        <form method="POST" action="{{ route('game.next-phase') }}">
            @csrf
            <button type="submit" class="text-xs text-gray-500 hover:text-gray-400 transition">
                → 次のフェーズへスキップ
            </button>
        </form>
    </div>
</div>

@if($state['is_game_over'])
<div class="mt-6 text-center">
    <a href="{{ route('game.ending') }}" class="btn-primary px-8 py-3 text-lg">
        🎊 エンディングを見る
    </a>
</div>
@endif

@endif
@endsection
