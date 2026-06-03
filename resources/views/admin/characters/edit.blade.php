@extends('layouts.admin')

@section('title', $character->name . 'を編集')

@section('content')
<div class="max-w-xl">
<form method="POST" action="{{ route('admin.characters.update', $character) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div class="bg-gray-800 rounded-xl p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">名前</label>
                <input type="text" name="name" value="{{ $character->name }}"
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">性別</label>
                <select name="gender" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
                    <option value="female" {{ $character->gender === 'female' ? 'selected' : '' }}>女性</option>
                    <option value="male" {{ $character->gender === 'male' ? 'selected' : '' }}>男性</option>
                    <option value="mysterious" {{ $character->gender === 'mysterious' ? 'selected' : '' }}>謎</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">性格</label>
            <textarea name="personality" rows="3"
                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">{{ $character->personality }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">説明</label>
            <textarea name="description" rows="4"
                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">{{ $character->description }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">表示順</label>
            <input type="number" name="sort_order" value="{{ $character->sort_order }}"
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_unlocked" id="is_unlocked" value="1"
                   {{ $character->is_unlocked ? 'checked' : '' }} class="rounded">
            <label for="is_unlocked" class="text-sm text-gray-400">解放済み</label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-2 rounded-lg text-sm transition">
            更新する
        </button>
        <a href="{{ route('admin.characters.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm transition">
            キャンセル
        </a>
    </div>
</form>
</div>
@endsection
