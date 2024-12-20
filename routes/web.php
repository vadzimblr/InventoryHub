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
Route::get('/admin-dashboard', function () {
    return Inertia::render('Admin');
});
Route::get('/procurement-manager-dashboard', function () {
    return Inertia::render('ProcurementManager');
});
Route::get('/account-manager-dashboard', function () {
    return Inertia::render('AccountManager');
});
Route::get('/accountant-dashboard', function () {
    return Inertia::render('Accountant');
});
Route::get('/storekeeper-dashboard', function () {
    return Inertia::render('Storekeeper');
});
