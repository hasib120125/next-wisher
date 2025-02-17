<?php

use App\Http\Controllers\Backend\TalentController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\UserPaymentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserMobilePaymentController;
use App\Models\Category;
use App\Models\TalentEarning;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;


Route::get('/category/{categorySlug}', function ($slug) {
    $category = Category::with(['parent'])->where('slug', $slug)->first();
    $talents = User::where(function ($query) use ($category) {
        $query->where('category_id', $category->id)
        ->orWhere('sub_category_id', $category->id);
    })
    ->where('status', 1)
    ->with(['talentEarningType' => function($query) {
        $query->where("type", "wish");
    }])
    ->whereNotNull('profile_image')
    ->whereNotNull('video_path')
    ->withAvg('rating', 'rating')
    ->get();
// return $talents;
    return Inertia::render('Backend/CategoryWiseItem', compact('talents', 'category'));
})->name('category.items');

// Route::middleware(['auth', 'verified'])->group(function(){
// });
Route::get('/talents/{id}/{username?}', [TalentController::class, 'talentDetailsForUser'])->name('item.details');

Route::middleware(['auth', 'verified', 'user-role:user'])->group(function(){
    Route::get('/user/dashboard', function () {
        return Inertia::render('Backend/UserDashboard/Guide', [
            'user' => Auth::user()
        ]);
    })->name('user.dashboard');

    Route::get('/user/dashboard/account', [UserController::class, 'account'])->name('user.account');
    Route::put('user/account/update', [UserController::class, 'accountUpdate'])->name('user.account.update');
    
    Route::get('/user/dashboard/following', [UserController::class, 'userFollowing'])->name('user.following');
    
    Route::get('/user/dashboard/subscription', [UserController::class, 'subscription'])->name('user.subscription');
    
    Route::get('/user/dashboard/history', [UserController::class, 'userPayHistory'])->name('user.history');


    // ------

    
    
    Route::post('/talents/{id}/follow', [TalentController::class, 'followTalents'])->name('item.followTalents');
    
    Route::get('/wish/{talentId}/{name?}', [PaymentController::class, 'wish'])->name('payment.info');
    Route::get('/my-life/{talentId}/subscribe', [PaymentController::class, 'subscribe'])->name('payment.subscribe');
    Route::get('/my-life/{talentId}', [PaymentController::class, 'myLife'])->name('payment.mylife');
    
    Route::get('/talent/tips/{talentId}/{name?}', [PaymentController::class, 'tipsPage'])->name('payment.tips.amount');
    Route::get('/talent-tips/{talentId}/{name?}', [PaymentController::class, 'tips'])->name('payment.tips');

    Route::get('/calender/{talentId}/{calenderId}', [PaymentController::class, 'calender'])->name('payment.calender');
    Route::get('/calender-purchase/{talentId}/{calenderId}', [PaymentController::class, 'calenderPurchase'])->name('payment.calenderPurchase');

    Route::get('/payment/gateway/{id}/{type}/{name?}', [PaymentController::class, 'gateway'])->name('payment.gateway');
    Route::post('/payment/pay/{id}/{type}', [UserPaymentController::class, 'createPayment'])->name('payment.createPayment');
    // Route::post('/mobile-pay/pay/{id}/{type}', [UserPaymentController::class, 'mobile_pay'])->name('payment.mobile_pay');
    Route::any('/mobile-pay/pay/{id}/{type}', [UserMobilePaymentController::class, 'createPayment'])->name('payment.mobile_pay_test');
    
    
    Route::get('/payment/tip', function () {
        return Inertia::render('Backend/Payment/Tip');
    })->name('payment.tip');

    Route::get('/get-notification', [UserController::class,'getNotification'])->name('getNotification');
    Route::post('/seen-notification/{id}', [UserController::class,'seenNotification'])->name('seenNotification');

});

Route::any('/mobile-pay/final-status-callback', [UserMobilePaymentController::class, 'mobile_pay_callback'])->name('payment.mobile_pay_callback');
Route::any('/mobile-pay/final-status-callback-server', [UserMobilePaymentController::class, 'mobile_pay_callback_server'])->name('payment.mobile_pay_callback_server');

Route::middleware(['auth', 'verified'])->group(function(){
    Route::post('mail-replay', [MailController::class, 'replay'])->name('mail.replay');
    // Route::get('mail-media/{path}', [MailController::class, 'getMedia'])->name('mail.getMedia');
    Route::get('/mail-media/{path}', [MailController::class, 'getMedia'])->name('mail.getMedia');
    Route::get('/mail-media/{path}/download/{id}', [MailController::class, 'downloadMedia'])->name('mail.downloadMedia');
    Route::get('/calendar/{calendar_id}/download', [MailController::class, 'downloadCalendar'])->name('calendar.download');
});