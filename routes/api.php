<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

Route::post('register', [ApiController::class, 'register']);

Route::post('login', [ApiController::class, 'login']);

Route::get('forgot-password', [ForgotPasswordController::class, 'create'])
    ->name('password.request');

Route::post('forgot-password', [ForgotPasswordController::class, 'store'])
    ->name('password.email');
Route::post('reset-password', [ResetPasswordController::class, 'reset']);

Route::group([
    "middleware" => ["auth:sanctum"],
], function () {
    Route::get("profile", [ApiController::class, 'show']);
    Route::put('users/{id}', [ApiController::class, 'updateUser']);

    Route::post("logout", [ApiController::class, 'logout']);
});

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
