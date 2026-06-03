@extends('layouts.admin')

@section('title', 'ゲーム設定')

@section('content')
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <div class="bg-gray-800 rounded-xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($settings as $key => $setting)
            <div>
                <label class="block text-sm text-gray-400 mb-1">{{ $setting->description }}</label>
                <input type="text" name="{{ $key }}" value="{{ $setting->value }}"
                       class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-white focus:outline-none focus:border-purple-400 text-sm">
                <p class="text-xs text-gray-600 mt-1">{{ $key }}</p>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                設定を保存
            </button>
        </div>
    </div>
</form>
@endsection
