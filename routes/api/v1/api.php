<?php

use App\Response;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\User\EmailController;
use App\Http\Controllers\Api\User\PaymentController;
use App\Http\Controllers\Api\User\ProfileController;
use App\Http\Controllers\Api\User\DashboardController;
use App\Http\Controllers\Api\Talent\TalentEarningController;
use App\Http\Controllers\Api\Talent\PaymentRequestController;
use App\Http\Controllers\Api\User\UserMobilePaymentController;
use App\Http\Controllers\Api\Talent\EmailController as TalentMailController;
use App\Http\Controllers\Api\Talent\ProfileController as TalentProfileController;
use App\Http\Controllers\Api\Talent\DashboardController as TalentDashboardController;
use App\Http\Controllers\Api\Talent\TalentDashboardController as TalentDashboardEarningController;

Route::get('/index', [HomeController::class, 'index']);
Route::get('/talents/{id}', [HomeController::class, 'talentsDetails']);
Route::get('/category/{slug}', [HomeController::class, 'categoryWiseTalents']);
Route::get('/languages', [HomeController::class, 'languages']);

Route::post('forgot-password-api', [DashboardController::class, 'forgotPassword']);

Route::middleware(['auth:sanctum'])->group(function(){
    Route::middleware([])->group(function(){
        Route::prefix('/user')->group(function(){
            Route::get('/dashboard', [DashboardController::class, 'dashboard']);
            Route::get('/profile', [ProfileController::class, 'profile']);
            Route::post('/profile-update', [ProfileController::class, 'profileUpdate']);
            Route::post('/change-password', [ProfileController::class, 'changePassword']);
            Route::delete('/profile-delete', [ProfileController::class, 'destroy']);

            Route::get('/mail-index', [EmailController::class, 'index']);
            Route::get('/mail-seen/{id}', [EmailController::class, 'seenMail']);
            Route::get('/mail-count', [EmailController::class, 'getMailCount']);

            Route::get('/mail-media-api/{path}/{id}', [EmailController::class, 'downloadMedia']);

            Route::post('/rating-submit', [EmailController::class, 'ratingSubmit']);
            Route::get('/rating-check/{user_id}/{talent_earning_id}', [EmailController::class, 'ratingCheck']);


            Route::get('/wish-payment-info/{talentId}/', [PaymentController::class, 'wishPaymentInfo']);
            Route::post('/payment/pay/{id}/{type}', [PaymentController::class, 'createPayment']);

            Route::any('/mobile-pay/pay/{id}/{type}', [UserMobilePaymentController::class, 'createPayment']);


        });
    });
    Route::prefix('/talent')->group(function(){
        Route::get('/dashboard', [TalentDashboardController::class, 'dashboard']);
        Route::get('/profile', [TalentProfileController::class, 'profile']);
        Route::post('/profile-update', [TalentProfileController::class, 'profileUpdate']);
        Route::post('/change-password', [TalentProfileController::class, 'changePassword']);
        Route::delete('/profile-delete', [TalentProfileController::class, 'destroy']);

        Route::post('/profile-setup', [TalentProfileController::class, 'profileSetup']);
        Route::post('/profile-setup-image', [TalentProfileController::class, 'saveProfile']);
        Route::post('/profile-setup-video', [TalentProfileController::class, 'saveVideo']);
        Route::post('/supported-languages', [TalentProfileController::class, 'supportedLanguages']);

        Route::middleware(['user-role:talent'])->group(function(){
            Route::get('/wish-request', [TalentEarningController::class, 'wishRequest']);
            Route::post('save-wish-request', [TalentEarningController::class, 'saveWishAmount']);

            Route::get('tips-request', [TalentEarningController::class, 'tips']);
            Route::post('save-tips-request', [TalentEarningController::class, 'saveTipsAmount']);

            Route::get('earnings', [TalentEarningController::class, 'earnings']);

            Route::post('/earning-filter', [TalentDashboardEarningController::class, 'getEarnings']);


        });

        Route::post('mail-replay', [TalentMailController::class, 'replay']);


        Route::get('/payout-info', [PaymentRequestController::class, 'payoutInfo']);
        Route::post('/payout-request', [PaymentRequestController::class, 'payoutRequest'])->name('talent.payoutRequest.api');
        Route::post('/mobile-payout-request', [PaymentRequestController::class, 'mobilePayoutRequest'])->name('talent.mobilePayoutRequest.api');
        Route::post('/bank-payout-canada', [PaymentRequestController::class, 'bankPayoutCanada'])->name('talent.bankPayoutCanada.api');
        Route::post('/bank-payout-europe', [PaymentRequestController::class, 'bankPayoutEurope'])->name('talent.bankPayoutEurope.api');
        Route::post('/bank-payout-outside', [PaymentRequestController::class, 'bankPayoutOutside'])->name('talent.bankPayoutOutside.api');

     });
    Route::get('/auth-check',function(){
        return Auth::user();
    });
});


Route::any('/mobile-pay/final-status-callback-api', [UserMobilePaymentController::class, 'mobile_pay_callback'])->name('payment.mobile_pay_callback.api');
// Route::any('/mobile-pay/final-status-callback-server', [UserMobilePaymentController::class, 'mobile_pay_callback_server'])->name('payment.mobile_pay_callback_server.api');

Route::any('/payment/stripe/wish/{token}', [PaymentController::class, 'wishpaymentStripeSuccess'])->name('payment.success.stripe.api');
Route::any('/payment/stripe/cancel', [PaymentController::class, 'cancel'])->name('payment.success.stripe.cancel');



Route::get('/push-notification-check', [PaymentRequestController::class, 'pushNotificationCheck']);

Route::get('video-player/{url}', [HomeController::class, 'videoPlayerWeb'])->name('apiWebPlayer.api');

Route::get('/api-check',function(){
    return "Api is working";
});


