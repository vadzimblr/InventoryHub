<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return json_encode(['user'=>$request->user()]);
})->middleware('auth:sanctum');

Route::get('/admin-dashboard', function (Request $request) {
    return response()->json([
        'message' => 'Admin Dashboard',
        'admin' => $request->user()
    ]);
})->middleware(['auth:sanctum','role:admin']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/test-route',function (Request $request) {
    return response()->json([
        'message' => 'Test Route',
    ]);
})->middleware(['auth:sanctum']);
