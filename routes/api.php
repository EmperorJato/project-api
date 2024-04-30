<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
});

//For Authenticated User
Route::middleware('auth:sanctum')->group(function () {

    // Route::apiResource('project', ProjectController::class);
    Route::controller(ProjectController::class)->prefix('project')->group(function () {
        
        Route::get('/', 'index');
        //Create
        Route::post('/create', 'store');
        //Read
        Route::get('/read', 'show');
        //Update
        Route::put('/update', 'update');
        //Delete
        Route::delete('/delete', 'destroy');
    });

    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('/profile', 'profile');
    });
});