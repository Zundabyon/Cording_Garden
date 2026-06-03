<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'character_id', 'category', 'difficulty', 'question_text',
        'code_snippet', 'options', 'correct_answer', 'explanation', 'is_active',
    ];

    protected $casts = [
        'options' => 'array',
        'is_active' => 'boolean',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function answers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    public function getDifficultyLabel(): string
    {
        return match($this->difficulty) {
            1 => '★☆☆',
            2 => '★★☆',
            3 => '★★★',
            default => '★☆☆',
        };
    }
}
