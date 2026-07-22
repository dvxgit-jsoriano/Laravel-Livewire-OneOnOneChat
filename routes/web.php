<?php

use App\Livewire\Pages\Auth\Login\Index as Login;
use App\Livewire\Pages\Auth\Register\Index as Register;
use App\Livewire\Pages\Chat\Dashboard\Index as ChatDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('chat');
});

Route::livewire('/login', Login::class)->name('login');
Route::livewire('/register', Register::class)->name('register');

Route::middleware(['auth'])->group(function () {
    Route::livewire('/chat', ChatDashboard::class)->name('chat');
});
