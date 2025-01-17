<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});
Route::get('/ui', function () {
  return view('ui');
});

// Login/Logout/Register
Volt::route('/login', 'login')->name('login');
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/');
});
Volt::route('/register', 'register');

// Protected routes here
Route::middleware('auth')->group(function () {
    Volt::route('/', 'index');
    Volt::route('/users', 'users.index');
    Volt::route('/users/create', 'users.create');
    Volt::route('/users/{user}/edit', 'users.edit');
    // ... more
});
