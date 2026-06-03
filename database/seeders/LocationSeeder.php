<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'slug' => 'home',
                'name' => '自宅',
                'description' => '自分の部屋でゆっくり勉強できる。',
                'available_on' => ['weekday_after', 'weekend_morning', 'weekend_afternoon'],
                'icon' => '🏠',
            ],
            [
                'slug' => 'school',
                'name' => '学校',
                'description' => '放課後の教室で自習できる。',
                'available_on' => ['weekday_after'],
                'icon' => '🏫',
            ],
            [
                'slug' => 'park',
                'name' => '公園',
                'description' => '自然の中でリフレッシュできる。',
                'available_on' => ['weekday_after', 'weekend_morning', 'weekend_afternoon'],
                'icon' => '🌳',
            ],
            [
                'slug' => 'town',
                'name' => '街',
                'description' => 'ショッピングやグルメを楽しめる。',
                'available_on' => ['weekday_after', 'weekend_morning', 'weekend_afternoon'],
                'icon' => '🏙️',
            ],
            [
                'slug' => 'beach',
                'name' => 'ビーチ',
                'description' => '海辺でのんびり過ごせる。',
                'available_on' => ['weekend_morning', 'weekend_afternoon'],
                'icon' => '🏖️',
            ],
            [
                'slug' => 'mountain',
                'name' => '山',
                'description' => '山登りでパワーアップ！',
                'available_on' => ['weekend_morning', 'weekend_afternoon'],
                'icon' => '⛰️',
            ],
            [
                'slug' => 'gym',
                'name' => 'ジム',
                'description' => 'トレーニングでHP回復力アップ。',
                'available_on' => ['weekday_after', 'weekend_morning', 'weekend_afternoon'],
                'icon' => '💪',
            ],
            [
                'slug' => 'game_center',
                'name' => 'ゲームセンター',
                'description' => 'ゲームで楽しくリフレッシュ！',
                'available_on' => ['weekday_after', 'weekend_afternoon'],
                'icon' => '🎮',
            ],
        ];

        foreach ($locations as $location) {
            Location::updateOrCreate(['slug' => $location['slug']], $location);
        }
    }
}
