<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'character_id', 'trigger_day', 'trigger_affection', 'trigger_action',
        'title', 'content', 'is_repeatable',
    ];

    protected $casts = [
        'content' => 'array',
        'is_repeatable' => 'boolean',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
