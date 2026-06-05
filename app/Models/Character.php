<?php

namespace App\Models;

use App\Services\DialogueService;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    protected $fillable = [
        'slug', 'name', 'name_kana', 'gender', 'personality', 'subject',
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
            'ruby' => 'from-red-600 to-red-700',
            'rails' => 'from-red-500 to-red-800',
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
            'ruby' => '💎',
            'rails' => '🚂',
            'html' => '📄',
            'css' => '🎨',
            'js' => '⚡',
            'typescript' => '💙',
            'vue' => '💚',
            'error' => '⛔',
            default => '💻',
        };
    }

    // プロフィール部分を取得（言語説明を除く）
    public function getCharacterProfile(): string
    {
        if (!$this->description) {
            return '';
        }
        
        $parts = explode('【言語について】', $this->description);
        return trim($parts[0]);
    }

    // 言語説明部分を取得
    public function getLanguageDescription(): ?string
    {
        if (!$this->description) {
            return null;
        }
        
        $parts = explode('【言語について】', $this->description);
        if (count($parts) > 1) {
            return trim($parts[1]);
        }
        
        return null;
    }

    // 好感度(0-100)に応じた開始セリフ
    public function getGreetingDialogue(int $affection): array
    {
        return DialogueService::get($this->slug, 'greeting', $affection);
    }

    // 正解時のセリフ
    public function getCorrectDialogue(int $affection): array
    {
        return DialogueService::get($this->slug, 'correct', $affection);
    }

    // 不正解時のセリフ
    public function getWrongDialogue(int $affection): array
    {
        return DialogueService::get($this->slug, 'wrong', $affection);
    }
}
