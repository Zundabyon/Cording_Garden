<?php

namespace App\Services;

use App\Models\User;

class ShareCardService
{
    public function generate(User $user): string
    {
        $save = $user->gameSave;
        $currentDay = $save ? $save->current_day : 1;

        $width = 800;
        $height = 400;

        $image = imagecreatetruecolor($width, $height);

        // Colors
        $bgColor = imagecolorallocate($image, 15, 15, 35);
        $accentColor = imagecolorallocate($image, 139, 92, 246);
        $whiteColor = imagecolorallocate($image, 255, 255, 255);
        $grayColor = imagecolorallocate($image, 156, 163, 175);
        $pinkColor = imagecolorallocate($image, 236, 72, 153);
        $greenColor = imagecolorallocate($image, 34, 197, 94);
        $yellowColor = imagecolorallocate($image, 234, 179, 8);

        // Background
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Border
        imagerectangle($image, 2, 2, $width - 3, $height - 3, $accentColor);
        imagerectangle($image, 4, 4, $width - 5, $height - 5, $accentColor);

        // Title
        $title = 'Cording Garden';
        imagestring($image, 5, 20, 20, $title, $accentColor);

        // Player info
        $playerName = $user->player_name ?? $user->name;
        imagestring($image, 4, 20, 60, 'Player: ' . $playerName, $whiteColor);
        imagestring($image, 3, 20, 90, 'Day: ' . $currentDay . ' / 90', $grayColor);

        // HP bar
        imagestring($image, 3, 20, 130, 'HP:', $whiteColor);
        $hpPercent = $user->max_hp > 0 ? ($user->hp / $user->max_hp) : 0;
        $hpBarWidth = 200;
        imagefilledrectangle($image, 80, 130, 80 + $hpBarWidth, 145, $grayColor);
        $hpFill = (int)($hpBarWidth * $hpPercent);
        $hpColor = $hpPercent > 0.5 ? $greenColor : ($hpPercent > 0.25 ? $yellowColor : $pinkColor);
        if ($hpFill > 0) {
            imagefilledrectangle($image, 80, 130, 80 + $hpFill, 145, $hpColor);
        }
        imagestring($image, 2, 290, 130, $user->hp . '/' . $user->max_hp, $whiteColor);

        // Stats
        imagestring($image, 4, 20, 175, 'Stats:', $whiteColor);
        imagestring($image, 3, 20, 200, 'Academic:  ' . $user->academic_power, $grayColor);
        imagestring($image, 3, 20, 220, 'Frontend:  ' . $user->frontend_power, $grayColor);
        imagestring($image, 3, 20, 240, 'Backend:   ' . $user->backend_power, $grayColor);

        // Top affections
        $topAffections = $user->affections()->with('character')->orderByDesc('level')->take(3)->get();
        if ($topAffections->isNotEmpty()) {
            imagestring($image, 4, 20, 285, 'Relationships:', $whiteColor);
            $yPos = 310;
            foreach ($topAffections as $aff) {
                $label = $aff->character->name . ': ' . $aff->level . ' pts';
                imagestring($image, 3, 20, $yPos, $label, $pinkColor);
                $yPos += 20;
            }
        }

        // Watermark
        imagestring($image, 2, $width - 200, $height - 25, 'Cording Garden #PHP学習', $grayColor);

        // Save to storage
        $path = storage_path('app/public/share_cards/');
        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $filename = 'card_' . $user->id . '_' . time() . '.png';
        $fullPath = $path . $filename;
        imagepng($image, $fullPath);
        imagedestroy($image);

        return $fullPath;
    }
}
