<?php

namespace App\Http\Controllers;

use App\Models\GameSave;
use App\Services\GameService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function __construct(private GameService $gameService)
    {
    }

    public function index()
    {
        $user = Auth::user();
        $state = $this->gameService->getCurrentGameState($user);
        return view('game.index', compact('state'));
    }

    public function start(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'player_name' => 'required|string|max:20',
        ]);

        $defaultName = config('game.protagonist.full_name', '風呂蔵 明空');
        $user->player_name = $request->player_name ?: $defaultName;
        $user->hp = 100;
        $user->max_hp = 100;
        $user->academic_power = 0;
        $user->frontend_power = 0;
        $user->backend_power = 0;
        $user->save();

        // Delete existing save
        $user->gameSave?->delete();

        // Create new save
        GameSave::create([
            'user_id' => $user->id,
            'current_day' => 1,
            'day_of_week' => 'monday',
            'phase' => 'morning',
            'is_weekend' => false,
        ]);

        return redirect()->route('game.index')->with('success', 'ゲームを開始しました！');
    }

    public function nextPhase()
    {
        $user = Auth::user();
        $save = $user->gameSave;

        if (!$save) {
            return redirect()->route('game.index');
        }

        $result = $this->gameService->advancePhase($user, $save);

        if ($this->gameService->checkEndGame($save)) {
            return redirect()->route('game.ending');
        }

        return redirect()->route('game.index')->with('phase_advanced', true);
    }
}
