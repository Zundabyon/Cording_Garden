<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['slug', 'name', 'description', 'available_on', 'icon'];

    protected $casts = [
        'available_on' => 'array',
    ];

    public function isAvailableFor(string $context): bool
    {
        return in_array($context, $this->available_on ?? []);
    }
}
