<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '管理パネル') - Cording Garden Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Noto Sans JP', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" class="font-bold text-purple-400 hover:text-purple-300">
                    ⚙️ Cording Garden Admin
                </a>
                <a href="{{ route('admin.questions.index') }}" class="text-sm text-gray-400 hover:text-white transition">問題管理</a>
                <a href="{{ route('admin.characters.index') }}" class="text-sm text-gray-400 hover:text-white transition">キャラクター</a>
                <a href="{{ route('admin.settings') }}" class="text-sm text-gray-400 hover:text-white transition">設定</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('game.index') }}" class="text-sm text-gray-400 hover:text-white transition">← ゲームへ</a>
            </div>
        </div>
    </nav>

    @if(session('success'))
    <div class="bg-green-700/30 border-b border-green-600/30 px-6 py-3 text-green-300 text-sm">
        ✓ {{ session('success') }}
    </div>
    @endif

    <main class="max-w-6xl mx-auto px-6 py-8">
        <h1 class="text-2xl font-bold text-white mb-6">@yield('title')</h1>
        @yield('content')
    </main>
</body>
</html>
