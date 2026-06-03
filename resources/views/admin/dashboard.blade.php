@extends('layouts.admin')

@section('title', 'ダッシュボード')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-gray-800 rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-blue-400">{{ $stats['users'] }}</div>
        <div class="text-sm text-gray-400 mt-1">ユーザー数</div>
    </div>
    <div class="bg-gray-800 rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-green-400">{{ $stats['questions'] }}</div>
        <div class="text-sm text-gray-400 mt-1">問題数</div>
    </div>
    <div class="bg-gray-800 rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-purple-400">{{ $stats['characters'] }}</div>
        <div class="text-sm text-gray-400 mt-1">キャラクター数</div>
    </div>
    <div class="bg-gray-800 rounded-xl p-5 text-center">
        <div class="text-3xl font-bold text-pink-400">{{ $stats['answers'] }}</div>
        <div class="text-sm text-gray-400 mt-1">総回答数</div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <a href="{{ route('admin.questions.index') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 transition">
        <h3 class="font-bold text-white mb-2">📚 問題管理</h3>
        <p class="text-sm text-gray-400">問題の追加・編集・削除</p>
    </a>
    <a href="{{ route('admin.characters.index') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 transition">
        <h3 class="font-bold text-white mb-2">👥 キャラクター管理</h3>
        <p class="text-sm text-gray-400">キャラクター設定の編集</p>
    </a>
    <a href="{{ route('admin.settings') }}" class="bg-gray-800 hover:bg-gray-700 rounded-xl p-6 transition">
        <h3 class="font-bold text-white mb-2">⚙️ ゲーム設定</h3>
        <p class="text-sm text-gray-400">HP・好感度などのパラメーター</p>
    </a>
</div>
@endsection
