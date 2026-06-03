<?php

namespace App\Http\Controllers;

use App\Models\Character;
use Illuminate\Http\Request;

class AdminCharacterController extends Controller
{
    public function index()
    {
        $characters = Character::orderBy('sort_order')->get();
        return view('admin.characters.index', compact('characters'));
    }

    public function edit(Character $character)
    {
        return view('admin.characters.edit', compact('character'));
    }

    public function update(Request $request, Character $character)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100',
            'gender' => 'required|string',
            'personality' => 'required|string',
            'subject' => 'required|string',
            'description' => 'nullable|string',
            'is_unlocked' => 'boolean',
            'sort_order' => 'integer',
        ]);

        $data['is_unlocked'] = $request->boolean('is_unlocked');
        $character->update($data);

        return redirect()->route('admin.characters.index')->with('success', 'キャラクターを更新しました。');
    }
}
