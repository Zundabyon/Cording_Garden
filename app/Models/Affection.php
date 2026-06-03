<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affection extends Model
{
    protected $fillable = ['user_id', 'character_id', 'level'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function getRelationshipLabel(): string
    {
        return match(true) {
            $this->level >= 80 => '愛してる',
            $this->level >= 60 => '大好き',
            $this->level >= 40 => '好き',
            $this->level >= 20 => '気になる',
            $this->level >= 10 => '友達',
            default => '知り合い',
        };
    }

    public function getHeartCount(): int
    {
        return (int) floor($this->level / 20);
    }
}
