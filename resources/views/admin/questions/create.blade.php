@extends('layouts.admin')

@section('title', '問題を追加')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.questions.store') }}" class="space-y-4">
    @csrf

    <div class="bg-gray-800 rounded-xl p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">キャラクター</label>
                <select name="character_id" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm" required>
                    @foreach($characters as $char)
                    <option value="{{ $char->id }}" {{ old('character_id') == $char->id ? 'selected' : '' }}>
                        {{ $char->getEmojiIcon() }} {{ $char->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">カテゴリー</label>
                <select name="category" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
                    <option value="multiple_choice">選択問題</option>
                    <option value="fill_blank">穴埋め問題</option>
                    <option value="code_complete">コード完成</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">難易度 (1-3)</label>
            <input type="number" name="difficulty" value="{{ old('difficulty', 1) }}" min="1" max="3"
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">問題文</label>
            <textarea name="question_text" rows="3"
                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm"
                      required>{{ old('question_text') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">コードスニペット（任意）</label>
            <textarea name="code_snippet" rows="3" placeholder="コードがある場合に入力"
                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm font-mono">{{ old('code_snippet') }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-2">選択肢（最低2つ必要）</label>
            @for($i = 0; $i < 4; $i++)
            <input type="text" name="options[]" value="{{ old('options.' . $i) }}"
                   placeholder="選択肢 {{ ['A','B','C','D'][$i] }}"
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm mb-2">
            @endfor
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">正解</label>
            <input type="text" name="correct_answer" value="{{ old('correct_answer') }}"
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm"
                   placeholder="正解の選択肢テキストをそのまま入力" required>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">解説</label>
            <textarea name="explanation" rows="3"
                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm"
                      required>{{ old('explanation') }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ old('is_active', '1') === '1' ? 'checked' : '' }}
                   class="rounded">
            <label for="is_active" class="text-sm text-gray-400">有効にする</label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-2 rounded-lg text-sm transition">
            問題を追加
        </button>
        <a href="{{ route('admin.questions.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm transition">
            キャンセル
        </a>
    </div>
</form>
</div>
@endsection
