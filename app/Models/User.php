<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'player_name',
        'hp',
        'max_hp',
        'academic_power',
        'frontend_power',
        'backend_power',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function gameSave()
    {
        return $this->hasOne(GameSave::class);
    }

    public function affections()
    {
        return $this->hasMany(Affection::class);
    }

    public function questionAnswers()
    {
        return $this->hasMany(QuestionAnswer::class);
    }

    public function dailyLogs()
    {
        return $this->hasMany(DailyLog::class);
    }

    public function getAffectionFor(int $characterId): int
    {
        $affection = $this->affections()->where('character_id', $characterId)->first();
        return $affection ? $affection->level : 0;
    }

    public function getHpPercentage(): int
    {
        if ($this->max_hp === 0) return 0;
        return (int) (($this->hp / $this->max_hp) * 100);
    }
}
