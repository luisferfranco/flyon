<?php

use Livewire\Volt\Volt;
use Illuminate\Support\Facades\Route;

Volt::route('/', 'index');
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
})->name('logout');
Volt::route('/register', 'register')->name('register');

// Protected routes here
Route::middleware('auth')->group(function () {
  Volt::route('/dashboard', 'dashboard')->name('dashboard');
  Volt::route('/users', 'users.index');
  Volt::route('/users/create', 'users.create');
  Volt::route('/users/{user}/edit', 'users.edit');
  // ... more
});
