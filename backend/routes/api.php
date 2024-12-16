<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth:api');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
