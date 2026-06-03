<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Support\Facades\Auth;

class CharacterController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $characters = Character::orderBy('sort_order')->get();
        $affections = $user->affections()->pluck('level', 'character_id');

        return view('game.characters', compact('characters', 'affections'));
    }

    public function show(string $slug)
    {
        $user = Auth::user();
        $character = Character::where('slug', $slug)->firstOrFail();
        $affection = $user->affections()->where('character_id', $character->id)->first();

        return view('game.character-profile', compact('character', 'affection'));
    }
}
