<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\VerifyEmailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [UserController::class, 'register'])
    ->name('user.register')
    ->middleware('guest');

Route::get('/email/verify/{id}/{hash}', VerifyEmailController::class)
    ->middleware('signed')
    ->name('verification.verify');

Route::post('/login', [UserController::class, 'login'])
    ->name('user.login')
    ->middleware('guest');

Route::post('/refresh', [UserController::class, 'refresh'])
    ->name('user.refresh')
    ->middleware('jwt');


Route::middleware(['jwt'])->group(function () {
    Route::post('/logout', [UserController::class, 'logout'])->name('user.logout');
});