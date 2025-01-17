<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});
Route::get('/ui', function () {
  return view('ui');
});
