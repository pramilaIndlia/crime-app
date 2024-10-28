<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\LocationController;


Route::get("reset-password", function () {
    return view('resetpasswordform');
});
Route::get("forget-password", function () {
    return view('forgetpasswordform');
});

Route::get('/', function () {
    return view('welcome');
});
