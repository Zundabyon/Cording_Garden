<?php

namespace App\Http\Controllers;

use App\Services\ShareCardService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ShareController extends Controller
{
    public function __construct(private ShareCardService $shareCardService)
    {
    }

    public function generateCard()
    {
        $user = Auth::user();
        $imagePath = $this->shareCardService->generate($user);

        return response()->file($imagePath, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="cording_garden_card.png"',
        ]);
    }

    public function shareToX()
    {
        $user = Auth::user();
        $save = $user->gameSave;
        $currentDay = $save ? $save->current_day : 1;

        $text = "【Cording Garden】 {$currentDay}日目！プログラミング学習継続中🌸\n#CordingGarden #PHP学習 #プログラミング";
        $cardUrl = route('share.card');

        $xUrl = 'https://twitter.com/intent/tweet?text=' . urlencode($text) . '&url=' . urlencode($cardUrl);

        return redirect()->away($xUrl);
    }
}
