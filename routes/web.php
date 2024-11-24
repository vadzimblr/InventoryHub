<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/login', function () {
    return Inertia::render('Login');
})->name('login')->middleware('guest');

Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register')->middleware('guest');

Route::match(['GET'],'/products', function () {
    return Inertia::render('Products');
});
Route::get('/admin-panel', function () {
    return Inertia::render('Admin');
});
