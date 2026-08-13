<?php

use App\Http\Controllers\Admin\EventController as AdminEventController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\AmericanoController;
use App\Http\Controllers\CommunityEventController;
use App\Http\Controllers\MatchController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// ─── Landing Page ─────────────────────────────────────────────────────────────
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ─── Public: Match Scorer ─────────────────────────────────────────────────────
Route::prefix('match')->name('match.')->group(function () {
    Route::get('/create', [MatchController::class, 'create'])->name('create');
    Route::post('/', [MatchController::class, 'store'])->name('store');
    Route::get('/{match}/scoreboard', [MatchController::class, 'scoreboard'])->name('scoreboard');
    Route::get('/{match}/state', [MatchController::class, 'state'])->name('state');
    Route::post('/{match}/add-point', [MatchController::class, 'addPoint'])->name('addPoint');
    Route::post('/{match}/undo', [MatchController::class, 'undoPoint'])->name('undo');
    Route::post('/{match}/finish', [MatchController::class, 'finish'])->name('finish');
    Route::get('/{match}/result', [MatchController::class, 'result'])->name('result');
});

// ─── Public: Americano Scorer ─────────────────────────────────────────────────
Route::prefix('americano')->name('americano.')->group(function () {
    Route::get('/create', [AmericanoController::class, 'create'])->name('create');
    Route::post('/', [AmericanoController::class, 'store'])->name('store');
    Route::get('/{tournament}/round/{round}', [AmericanoController::class, 'round'])->name('round');
    Route::get('/{tournament}/game/{game}/scoreboard', [AmericanoController::class, 'scoreboard'])->name('scoreboard');
    Route::post('/{tournament}/game/{game}/add-point', [AmericanoController::class, 'addPoint'])->name('addPoint');
    Route::post('/{tournament}/game/{game}/finish', [AmericanoController::class, 'finishGame'])->name('finishGame');
    Route::post('/{tournament}/round/{round}/advance', [AmericanoController::class, 'advanceRound'])->name('advanceRound');
    Route::get('/{tournament}/leaderboard', [AmericanoController::class, 'leaderboard'])->name('leaderboard');
});

// ─── Auth (Breeze) ─────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── Member Routes (auth + approved) ─────────────────────────────────────────
Route::middleware(['auth', 'approved'])->group(function () {
    // Community Events
    Route::prefix('community')->name('community.')->group(function () {
        Route::get('/events', [CommunityEventController::class, 'index'])->name('events.index');
        Route::get('/events/{event}', [CommunityEventController::class, 'show'])->name('events.show');
        Route::post('/events/{event}/register', [CommunityEventController::class, 'register'])->name('events.register');
        Route::post('/events/{event}/cancel', [CommunityEventController::class, 'cancelRegistration'])->name('events.cancel');
    });

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Admin Routes (auth + approved + admin) ───────────────────────────────────
Route::middleware(['auth', 'approved', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Member Management
    Route::prefix('members')->name('members.')->group(function () {
        Route::get('/', [AdminMemberController::class, 'index'])->name('index');
        Route::post('/{user}/approve', [AdminMemberController::class, 'approve'])->name('approve');
        Route::post('/{user}/reject', [AdminMemberController::class, 'reject'])->name('reject');
    });

    // Event CRUD
    Route::resource('events', AdminEventController::class)->names('events');
});
