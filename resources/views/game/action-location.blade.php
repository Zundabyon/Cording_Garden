@extends('layouts.game')

@section('title', '外出')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="glass-panel rounded-xl p-6 mb-6">
        <h2 class="text-xl font-bold text-purple-300 mb-1">どこへ行く？</h2>
        <p class="text-sm text-gray-400">
            {{ $context === 'weekend_morning' ? '週末の朝' : ($context === 'weekend_afternoon' ? '週末の午後' : '放課後') }}
            に行けるスポット
        </p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
        @forelse($locations as $location)
        <div class="glass-panel rounded-xl p-5 text-center hover:bg-white/10 transition cursor-pointer group">
            <div class="text-4xl mb-3 group-hover:scale-110 transition">{{ $location->icon }}</div>
            <h3 class="font-bold text-white mb-1">{{ $location->name }}</h3>
            <p class="text-xs text-gray-400">{{ $location->description }}</p>
            <form method="POST" action="{{ route('game.action') }}" class="mt-3">
                @csrf
                <input type="hidden" name="action" value="go_out">
                <input type="hidden" name="location" value="{{ $location->slug }}">
                <button type="submit" class="btn-primary text-xs px-4 py-2 w-full">
                    ここへ行く
                </button>
            </form>
        </div>
        @empty
        <div class="col-span-full text-center text-gray-500 py-8">
            今の時間帯に行けるスポットがありません。
        </div>
        @endforelse
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('game.index') }}" class="text-sm text-gray-500 hover:text-gray-400 transition">
            ← 戻る
        </a>
    </div>
</div>
@endsection
