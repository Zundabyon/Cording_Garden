<?php

use App\Http\Controllers\ActionController;
use App\Http\Controllers\AdminCharacterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminQuestionController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ShareController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('game.index');
    }
    return view('welcome');
});

// Game routes
Route::middleware(['auth'])->group(function () {
    Route::get('/game', [GameController::class, 'index'])->name('game.index');
    Route::post('/game/start', [GameController::class, 'start'])->name('game.start');
    Route::post('/game/next-phase', [GameController::class, 'nextPhase'])->name('game.next-phase');
    Route::get('/game/ending', function () { return view('game.ending'); })->name('game.ending');

    Route::post('/game/action', [ActionController::class, 'perform'])->name('game.action');

    Route::post('/question/answer', [QuestionController::class, 'answer'])->name('question.answer');

    Route::get('/characters', [CharacterController::class, 'index'])->name('characters.index');
    Route::get('/characters/{slug}', [CharacterController::class, 'show'])->name('characters.show');

    Route::get('/share/card', [ShareController::class, 'generateCard'])->name('share.card');
    Route::get('/share/x', [ShareController::class, 'shareToX'])->name('share.x');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin routes
Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('questions', AdminQuestionController::class);
    Route::resource('characters', AdminCharacterController::class)->only(['index', 'edit', 'update']);
    Route::get('settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::get('share-card/{userId}', [AdminController::class, 'generateShareCard'])->name('share-card');
});

require __DIR__.'/auth.php';
