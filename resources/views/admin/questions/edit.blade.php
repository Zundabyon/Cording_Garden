@extends('layouts.admin')

@section('title', '問題を編集')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('admin.questions.update', $question) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div class="bg-gray-800 rounded-xl p-6 space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">キャラクター</label>
                <select name="character_id" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
                    @foreach($characters as $char)
                    <option value="{{ $char->id }}" {{ $question->character_id == $char->id ? 'selected' : '' }}>
                        {{ $char->getEmojiIcon() }} {{ $char->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">カテゴリー</label>
                <select name="category" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
                    <option value="multiple_choice" {{ $question->category === 'multiple_choice' ? 'selected' : '' }}>選択問題</option>
                    <option value="fill_blank" {{ $question->category === 'fill_blank' ? 'selected' : '' }}>穴埋め問題</option>
                    <option value="code_complete" {{ $question->category === 'code_complete' ? 'selected' : '' }}>コード完成</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">難易度</label>
            <input type="number" name="difficulty" value="{{ $question->difficulty }}" min="1" max="3"
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">問題文</label>
            <textarea name="question_text" rows="3"
                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">{{ $question->question_text }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">コードスニペット</label>
            <textarea name="code_snippet" rows="3"
                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm font-mono">{{ $question->code_snippet }}</textarea>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-2">選択肢</label>
            @for($i = 0; $i < 4; $i++)
            <input type="text" name="options[]" value="{{ $question->options[$i] ?? '' }}"
                   placeholder="選択肢 {{ ['A','B','C','D'][$i] }}"
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm mb-2">
            @endfor
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">正解</label>
            <input type="text" name="correct_answer" value="{{ $question->correct_answer }}"
                   class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-1">解説</label>
            <textarea name="explanation" rows="3"
                      class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">{{ $question->explanation }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                   {{ $question->is_active ? 'checked' : '' }} class="rounded">
            <label for="is_active" class="text-sm text-gray-400">有効にする</label>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-2 rounded-lg text-sm transition">
            更新する
        </button>
        <a href="{{ route('admin.questions.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg text-sm transition">
            キャンセル
        </a>
    </div>
</form>
</div>
@endsection
