<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\LocationController;


Route::post('register', [ApiController::class, 'register']);

Route::post('login', [ApiController::class, 'login']);

Route::get('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');

// Reset Password Routes
Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
Route::group([
    "middleware" => ["auth:sanctum"],
], function () {
    Route::get("profile", [ApiController::class, 'show']);
    Route::put('users/{id}', [ApiController::class, 'updateUser']);

    Route::post("logout", [ApiController::class, 'logout']);
});

Route::post('/geocode', [LocationController::class, 'geocode']);
Route::post('/distance', [LocationController::class, 'distance']);

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
