@extends('layouts.admin')

@section('title', '問題管理')

@section('content')
<div class="flex justify-between items-center mb-4">
    <span class="text-sm text-gray-400">{{ $questions->total() }}件</span>
    <a href="{{ route('admin.questions.create') }}" class="bg-purple-600 hover:bg-purple-500 text-white px-4 py-2 rounded-lg text-sm transition">
        + 問題を追加
    </a>
</div>

<div class="bg-gray-800 rounded-xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-700 text-gray-300">
            <tr>
                <th class="px-4 py-3 text-left">ID</th>
                <th class="px-4 py-3 text-left">キャラ</th>
                <th class="px-4 py-3 text-left">問題</th>
                <th class="px-4 py-3 text-left">難易度</th>
                <th class="px-4 py-3 text-left">状態</th>
                <th class="px-4 py-3 text-left">操作</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($questions as $question)
            <tr class="hover:bg-gray-700/50 transition">
                <td class="px-4 py-3 text-gray-400">{{ $question->id }}</td>
                <td class="px-4 py-3">
                    <span class="flex items-center gap-1">
                        {{ $question->character->getEmojiIcon() }}
                        <span class="text-xs">{{ $question->character->name }}</span>
                    </span>
                </td>
                <td class="px-4 py-3 max-w-xs">
                    <p class="truncate text-gray-200">{{ $question->question_text }}</p>
                </td>
                <td class="px-4 py-3 text-yellow-400">{{ $question->getDifficultyLabel() }}</td>
                <td class="px-4 py-3">
                    <span class="{{ $question->is_active ? 'text-green-400' : 'text-gray-500' }} text-xs">
                        {{ $question->is_active ? '有効' : '無効' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.questions.edit', $question) }}"
                           class="text-blue-400 hover:text-blue-300 text-xs transition">編集</a>
                        <form method="POST" action="{{ route('admin.questions.destroy', $question) }}"
                              onsubmit="return confirm('削除しますか？')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-400 hover:text-red-300 text-xs transition">削除</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">問題がまだありません</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $questions->links() }}</div>
@endsection
