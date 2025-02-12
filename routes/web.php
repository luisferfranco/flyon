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

// Registro bloqueado por seguridad, las cuentas se crean individualmente
// Volt::route('/register', 'register')->name('register');

// Protected routes here
Route::middleware('auth')->group(function () {
  Volt::route('/dashboard', 'users.index')->name('dashboard');
  Volt::route('/users/', 'users.index')->name('users.index');
  Volt::route('/user/{user?}', 'users.show')->name('user.show');

  Volt::route('/proyectos/', 'proyectos.index')->name('proyectos.index');
  Volt::route('/proyecto/{proyecto?}', 'proyectos.show')->name('proyecto.show');

  Volt::route('/tarea/create', 'tarea.create')->name('tarea.create');
  Volt::route('/tarea/{tarea}', 'tarea.show')->name('tarea.show');
  Volt::route('/tarea/{tarea}/edit', 'tarea.show')->name('tarea.edit');

});

// Volt::route('/test/{proyecto}/{tarea?}', 'test');
