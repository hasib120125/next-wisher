<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\DashboardController;
use App\Http\Controllers\Api\User\Auth\LoginController;
use App\Http\Controllers\Api\User\Auth\RegisterController;

Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::get('signup-info', [LoginController::class, 'signupInfo']);
Route::middleware(['auth:sanctum'])->group(function(){
    Route::post('logout', [LoginController::class, 'logout']);
    Route::post('email-verification', [DashboardController::class, 'resendEmail']);
});