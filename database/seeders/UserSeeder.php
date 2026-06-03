<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::updateOrCreate(
            ['email' => 'admin@cording-garden.test'],
            [
                'name' => 'Admin',
                'email' => 'admin@cording-garden.test',
                'password' => Hash::make('password'),
                'is_admin' => true,
                'player_name' => 'Admin',
                'hp' => 100,
                'max_hp' => 100,
            ]
        );

        // Test player
        User::updateOrCreate(
            ['email' => 'player@cording-garden.test'],
            [
                'name' => 'Test Player',
                'email' => 'player@cording-garden.test',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'player_name' => 'テストプレイヤー',
                'hp' => 100,
                'max_hp' => 100,
            ]
        );
    }
}
