<?php

namespace App\Http\Controllers;

use App\Models\AdminSetting;
use App\Models\Character;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\User;
use App\Services\ShareCardService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'questions' => Question::count(),
            'characters' => Character::count(),
            'answers' => QuestionAnswer::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function settings()
    {
        $settings = AdminSetting::all()->keyBy('key');
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        foreach ($data as $key => $value) {
            AdminSetting::set($key, $value);
        }

        return redirect()->route('admin.settings')->with('success', '設定を更新しました。');
    }

    public function generateShareCard(int $userId)
    {
        $user = User::findOrFail($userId);
        $service = app(ShareCardService::class);
        $imagePath = $service->generate($user);

        return response()->file($imagePath, [
            'Content-Type' => 'image/png',
        ]);
    }
}
