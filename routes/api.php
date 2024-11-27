<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserCredentialsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/role', [UserCredentialsController::class, 'getUserRole']);

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/showAllUsers', [AdminController::class, 'showAllUsers']);
        Route::get('/admin/showAllRoles', [AdminController::class, 'showAllRoles']);
        Route::post('/admin/addUser', [AdminController::class, 'addUser']);
    });
    Route::get('/suppliers',[SupplierController::class,'showAllSuppliers']);
    Route::post('/supplier',[SupplierController::class,'addSupplier']);
    Route::delete('/supplier/{id}',[SupplierController::class,'deleteSupplier']);
    Route::put('/supplier/{id}',[SupplierController::class,'updateSupplier']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
