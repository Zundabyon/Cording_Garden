<?php

namespace Database\Seeders;

use App\Models\AdminSetting;
use Illuminate\Database\Seeder;

class AdminSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'max_affection_per_character', 'value' => '100', 'description' => 'キャラクターの最大好感度'],
            ['key' => 'affection_per_correct_answer', 'value' => '10', 'description' => '正解時の好感度アップ量'],
            ['key' => 'affection_per_wrong_answer', 'value' => '0', 'description' => '不正解時の好感度変化量'],
            ['key' => 'hp_cost_study', 'value' => '10', 'description' => '勉強時のHP消費'],
            ['key' => 'hp_cost_exercise', 'value' => '15', 'description' => '運動時のHP消費'],
            ['key' => 'hp_recovery_rest', 'value' => '30', 'description' => '休憩時のHP回復量'],
            ['key' => 'hp_recovery_sleep', 'value' => '50', 'description' => '就寝時のHP回復量'],
            ['key' => 'game_start_date', 'value' => '2024-01-08', 'description' => 'ゲーム開始日（3学期始業式）'],
            ['key' => 'game_total_days', 'value' => '90', 'description' => 'ゲームの総日数'],
        ];

        foreach ($settings as $setting) {
            AdminSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
