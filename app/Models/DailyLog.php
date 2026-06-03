<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    protected $fillable = [
        'user_id', 'day', 'phase', 'action_type', 'location_slug',
        'hp_change', 'power_changes', 'notes',
    ];

    protected $casts = [
        'power_changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
