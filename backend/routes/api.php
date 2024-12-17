<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::middleware('auth:api')->group(function () {
    Route::post('/contacts', [ContactController::class, 'store']);
});
Route::middleware('auth:api')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index']);
});
Route::middleware('auth:api')->group(function () {
    Route::put('/contacts/{id}', [ContactController::class, 'update']);
});
Route::middleware('auth:api')->group(function () {
    Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);
});


