<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});
Route::get('/login', function () {
    return response()->json(['message' => 'Unauthorized'], 401);
})->name('login');
Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');
