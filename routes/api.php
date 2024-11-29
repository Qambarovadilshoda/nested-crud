<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FolderController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::apiResource('/folders', FolderController::class);
    Route::get('/search', [FolderController::class, 'search']);
    Route::delete('/logout', [AuthController::class, 'logout']);
});

