<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
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

    Route::get('client/products/',[ProductController::class,'showAllProductsForClients']);
    Route::post('/product',[ProductController::class,'createProduct']);
    Route::put('/product/{id}',[ProductController::class,'updateProduct']);
    Route::get('/products',[ProductController::class,'showAllProducts']);
    Route::get('/product/{id}',[ProductController::class,'showProduct']);
    Route::post('/product/{id}/add-stock/{amount}',[ProductController::class,'addStock']);
    Route::post('/product/{id}/remove-stock/{amount}',[ProductController::class,'removeStock']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
