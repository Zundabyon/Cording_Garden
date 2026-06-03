<!DOCTYPE html>
<html lang="ja" x-data="{ showMenu: false }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cording Garden') - コーディングガーデン</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&display=swap');
        body { font-family: 'Noto Sans JP', sans-serif; }
        .game-bg { background: linear-gradient(135deg, #0f0c29, #302b63, #24243e); }
        .glass-panel {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .heart-full { color: #ec4899; }
        .heart-empty { color: rgba(255,255,255,0.2); }
        .btn-game {
            @apply px-4 py-2 rounded-lg font-medium transition-all duration-200 hover:scale-105 active:scale-95;
        }
        .btn-primary { @apply btn-game bg-purple-600 hover:bg-purple-500 text-white; }
        .btn-secondary { @apply btn-game bg-gray-700 hover:bg-gray-600 text-white; }
        .btn-danger { @apply btn-game bg-red-600 hover:bg-red-500 text-white; }
        .btn-success { @apply btn-game bg-green-600 hover:bg-green-500 text-white; }
        .hp-bar { transition: width 0.5s ease; }
    </style>
</head>
<body class="game-bg min-h-screen text-white">

    {{-- Status Bar --}}
    <header class="glass-panel sticky top-0 z-50 px-4 py-2">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            {{-- Game Title --}}
            <a href="{{ route('game.index') }}" class="text-lg font-bold text-purple-300 hover:text-purple-200 transition">
                🌸 Cording Garden
            </a>

            {{-- Status Info --}}
            @auth
            <div class="flex items-center gap-4 text-sm">
                {{-- Day counter --}}
                @if(isset($state) && ($state['has_save'] ?? false))
                <div class="glass-panel px-3 py-1 rounded-full text-xs">
                    <span class="text-purple-300">Day</span>
                    <span class="font-bold text-white mx-1">{{ $state['current_day'] }}</span>
                    <span class="text-gray-400">/ {{ $state['total_days'] }}</span>
                </div>
                {{-- Phase --}}
                <div class="glass-panel px-3 py-1 rounded-full text-xs">
                    <span class="text-yellow-300">{{ $state['phase_label'] }}</span>
                </div>
                {{-- HP --}}
                @php $user = auth()->user(); @endphp
                <div class="flex items-center gap-2">
                    <span class="text-red-400 text-xs">HP</span>
                    <div class="w-24 bg-gray-700 rounded-full h-3 overflow-hidden">
                        <div class="hp-bar h-full rounded-full {{ $user->getHpPercentage() > 50 ? 'bg-green-500' : ($user->getHpPercentage() > 25 ? 'bg-yellow-500' : 'bg-red-500') }}"
                             style="width: {{ $user->getHpPercentage() }}%"></div>
                    </div>
                    <span class="text-xs text-gray-300">{{ $user->hp }}/{{ $user->max_hp }}</span>
                </div>
                @endif

                {{-- Nav icons --}}
                <a href="{{ route('characters.index') }}" class="text-pink-300 hover:text-pink-200 transition" title="キャラクター">💕</a>
                @if(auth()->user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="text-yellow-300 hover:text-yellow-200 transition" title="管理">⚙️</a>
                @endif

                {{-- User menu --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="glass-panel px-3 py-1 rounded-full text-xs hover:bg-white/10 transition">
                        {{ auth()->user()->player_name ?? auth()->user()->name }} ▾
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                         class="absolute right-0 top-8 glass-panel rounded-lg py-1 w-36 z-50">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-xs hover:bg-white/10 transition">プロフィール</a>
                        <a href="{{ route('share.x') }}" class="block px-4 py-2 text-xs hover:bg-white/10 transition">Xでシェア</a>
                        <hr class="border-white/10 my-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="block w-full text-left px-4 py-2 text-xs hover:bg-white/10 transition text-red-400">
                                ログアウト
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endauth
        </div>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
         class="fixed top-16 right-4 z-50 glass-panel bg-green-500/20 border-green-500/30 px-4 py-3 rounded-lg text-green-300 text-sm max-w-xs">
        ✓ {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 4000)"
         class="fixed top-16 right-4 z-50 glass-panel bg-red-500/20 border-red-500/30 px-4 py-3 rounded-lg text-red-300 text-sm max-w-xs">
        ✗ {{ session('error') }}
    </div>
    @endif

    {{-- Main Content --}}
    <main class="max-w-4xl mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="text-center text-gray-600 text-xs py-4">
        Cording Garden &copy; {{ date('Y') }} - プログラミング恋愛シミュレーション
    </footer>

</body>
</html>
