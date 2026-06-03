<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSave extends Model
{
    protected $fillable = [
        'user_id', 'current_day', 'day_of_week', 'phase', 'is_weekend',
    ];

    protected $casts = [
        'is_weekend' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPhaseLabel(): string
    {
        return match($this->phase) {
            'morning' => '朝',
            'afternoon' => '昼',
            'after_school' => '放課後',
            'night' => '夜',
            default => $this->phase,
        };
    }

    public function getDayOfWeekLabel(): string
    {
        return match($this->day_of_week) {
            'monday' => '月',
            'tuesday' => '火',
            'wednesday' => '水',
            'thursday' => '木',
            'friday' => '金',
            'saturday' => '土',
            'sunday' => '日',
            default => '？',
        };
    }
}
