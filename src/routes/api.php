<?php

use App\Http\Actions\Auth\LoginAction;
use App\Http\Actions\User\UserRegisterAction;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('users')->group(function () {
    Route::post('register', UserRegisterAction::class);
    Route::post('login', LoginAction::class);
});

Route::middleware('auth:sanctum')->prefix('users')->group(function () {
    Route::get('/me', function () {
        dd(auth()->user());
    });
});