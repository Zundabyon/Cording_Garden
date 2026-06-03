<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'slug', 'name', 'gender', 'personality', 'subject',
        'description', 'is_unlocked', 'sort_order',
    ];

    protected $casts = [
        'is_unlocked' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function affections()
    {
        return $this->hasMany(Affection::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function getColorClass(): string
    {
        return match($this->subject) {
            'php' => 'from-purple-500 to-indigo-600',
            'laravel' => 'from-red-500 to-pink-600',
            'html' => 'from-orange-500 to-red-500',
            'css' => 'from-blue-400 to-cyan-500',
            'js' => 'from-yellow-400 to-amber-500',
            'typescript' => 'from-blue-600 to-blue-800',
            'vue' => 'from-green-400 to-emerald-500',
            'error' => 'from-gray-700 to-gray-900',
            default => 'from-gray-400 to-gray-600',
        };
    }

    public function getEmojiIcon(): string
    {
        return match($this->subject) {
            'php' => '🐘',
            'laravel' => '🔴',
            'html' => '📄',
            'css' => '🎨',
            'js' => '⚡',
            'typescript' => '💙',
            'vue' => '💚',
            'error' => '⛔',
            default => '💻',
        };
    }
}
