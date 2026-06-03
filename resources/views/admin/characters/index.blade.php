@extends('layouts.admin')

@section('title', 'キャラクター管理')

@section('content')
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    @foreach($characters as $char)
    <div class="bg-gray-800 rounded-xl p-4 text-center">
        <div class="text-4xl mb-2">{{ $char->getEmojiIcon() }}</div>
        <h3 class="font-medium text-white text-sm mb-1">{{ $char->name }}</h3>
        <p class="text-xs text-gray-500 mb-2">{{ strtoupper($char->subject) }}</p>
        <span class="{{ $char->is_unlocked ? 'text-green-400' : 'text-gray-500' }} text-xs block mb-3">
            {{ $char->is_unlocked ? '解放済み' : '未解放' }}
        </span>
        <a href="{{ route('admin.characters.edit', $char) }}"
           class="bg-gray-700 hover:bg-gray-600 text-white px-3 py-1 rounded-lg text-xs transition">
            編集
        </a>
    </div>
    @endforeach
</div>
@endsection
