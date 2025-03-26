<?php

use App\Http\Controllers\MessagesController;
use App\Models\Message;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard', [
        'messages' => Message::with('user')->get()->append('time'),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('messages', [MessagesController::class, 'index'])->name('messages.index');
Route::post('messages', [MessagesController::class, 'store'])->name('messages.store');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
