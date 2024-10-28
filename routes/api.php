<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\LocationController;


Route::post('register', [ApiController::class, 'register']);
Route::post('login', [ApiController::class, 'login']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset']);


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
