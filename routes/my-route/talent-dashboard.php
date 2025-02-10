<?php

use App\Http\Controllers\Backend\PaymentRequestController;
use App\Http\Controllers\Backend\TalentController;
use App\Http\Controllers\Backend\TalentEarningController;
use App\Http\Controllers\Backend\TalentManagerController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TalentDashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Session;


Route::middleware(['auth', 'verified'])->group(function(){
    Route::get('/talent/dashboard/profile/setup', [TalentController::class, 'ProfileSetup'])->name('talent.profile.setup');
    Route::post('/talent/account/update-profile', [TalentController::class, 'profileUpdate'])->name('talent.profile.update');
    Route::post('/talent/dashboard/save-cover', [TalentController::class, 'saveCover'])->name('talent.saveCover');
    Route::post('/talent/dashboard/save-profile', [TalentController::class, 'saveProfile'])->name('talent.saveProfile');
});
Route::get('/registered/{token}', function($token){
    if (session('talent_register_success') == null) {
        return redirect()->route('home');
    }
    return Inertia::render('TalentSuccess');
})->name('talent.registerSuccess');

Route::middleware(['auth', 'verified', 'user-role:talent'])->group(function(){

    Route::get('/talent/dashboard', function () {
        return Inertia::render('Backend/TalentDashboard/Guide');
    })->name('talent.dashboard');

    Route::get('/talent/preview/{id}', [TalentController::class, 'talentDetailsForUser'])->name('item.preview');

    Route::get('/talent/dashboard/account', [TalentController::class, 'account'])->name('talent.account');
    Route::put('/talent/account/update', [TalentController::class, 'accountUpdate'])->name('talent.account.update');
    
    
    
    Route::get('/talent/dashboard/tips', [TalentEarningController::class, 'tips'])->name('talent.tips');
    
    Route::get('/talent/dashboard/my-life', [TalentEarningController::class, 'myLife'])->name('talent.myLife');
    Route::post('/talent/dashboard/my-life/post', [PostController::class, 'store'])->name('talent.myLife.post');
    Route::post('/talent/dashboard/my-life/postDelete/{id}', [PostController::class, 'postDelete'])->name('talent.myLife.postDelete');

    Route::get('/talent/dashboard/wish-request', [TalentEarningController::class, 'wishRequest'])->name('talent.wish.request');
    Route::post('/talent/dashboard/save-wish-request', [TalentEarningController::class, 'saveWishAmount'])->name('talent.wish.saveAmount');
    
    Route::get('/talent/dashboard/manager', [TalentManagerController::class, 'manager'])->name('talent.manager.index');

    Route::post('/talent/dashboard/save-mylife-amount', [TalentEarningController::class, 'saveMylifeAmount'])->name('talent.mylife.saveAmount');
    Route::post('/talent/dashboard/save-tips-amount', [TalentEarningController::class, 'saveTipsAmount'])->name('talent.tips.saveAmount');
    
    Route::get('/talent/dashboard/analytics', [TalentDashboardController::class, 'index'])->name('talent.analytics');
    Route::post('/talent/get-earnings', [TalentDashboardController::class, 'getEarnings'])->name('talent.getEarnings');
    
    Route::get('/talent/dashboard/earnings', [PaymentRequestController::class, 'index'])->name('talent.payout');
    Route::post('/talent/dashboard/payout-request', [PaymentRequestController::class, 'payoutRequest'])->name('talent.payoutRequest');
    Route::post('/talent/dashboard/mobile-payout-request', [PaymentRequestController::class, 'mobilePayoutRequest'])->name('talent.mobilePayoutRequest');
    Route::post('/talent/dashboard/bank-payout-canada', [PaymentRequestController::class, 'bankPayoutCanada'])->name('talent.bankPayoutCanada');
    Route::post('/talent/dashboard/bank-payout-europe', [PaymentRequestController::class, 'bankPayoutEurope'])->name('talent.bankPayoutEurope');
    Route::post('/talent/dashboard/bank-payout-outside', [PaymentRequestController::class, 'bankPayoutOutside'])->name('talent.bankPayoutOutside');
});

