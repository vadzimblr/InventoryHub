<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/admin-dashboard', function (Request $request) {
    return response()->json([
        'message' => 'Admin Dashboard',
        'admin' => $request->user()
    ]);
})->middleware(['auth:sanctum','role:admin']);

Route::post('/login', [AuthController::class, 'login']);
