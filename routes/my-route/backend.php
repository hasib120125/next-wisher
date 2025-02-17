<?php

use App\Http\Controllers\Backend\EmailController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/mail', [EmailController::class, 'index'])->name('mail');
    Route::get('/get-mail', [EmailController::class, 'getMail'])->name('getMail');
    Route::post('/mail/{id}/seen-mail', [EmailController::class, 'seenMail'])->name('seenMail');
    Route::post('/get-mail-count', [EMailController::class, 'getMailCount'])->name('getMailCount');
    Route::delete('/fource_delete_account', [UserController::class, 'fource_delete_account'])->name('admin.fource_delete_account');
});

require_once('user-dashboard.php');
require_once('talent-dashboard.php');
require_once('admin.php');

















Route::middleware('auth',  'verified')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});