<?php

use App\Http\Controllers\Backend\AdminAnalyticsController;
use App\Http\Controllers\Backend\AdminPasswordController;
use App\Http\Controllers\Backend\AdminPaymentController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\CountryController;
use App\Http\Controllers\Backend\FaqController;
use App\Http\Controllers\Backend\FeaturedVideoController;
use App\Http\Controllers\Backend\HomeConfigController;
use App\Http\Controllers\Backend\OcassionController;
use App\Http\Controllers\Backend\SecurityCodeController;
use App\Http\Controllers\Backend\TalentController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\VisitorController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'verified', 'user-role:admin', 'verify_security_code:guest'])->group(function() {
    Route::get('/admin/verify-security-code', [SecurityCodeController::class, 'codeForm'])->name('securityCodeForm');
    Route::post('/admin/verify-security-code', [SecurityCodeController::class, 'codeSubmit'])->name('securityCodeSubmit');
});

Route::middleware(['auth', 'verified', 'user-role:admin', 'verify_security_code'])->group(function()
{    
    Route::post('/get-visitors', [VisitorController::class, 'getVisitors'])->name('get_visitors');
    Route::post('/get-online-visitors', [VisitorController::class, 'getOnlineVisitors'])->name('online_visitors');
    Route::post('/get-visitors-analytics-date', [VisitorController::class, 'getVisitorsAnalyticsDate'])->name('getVisitorsAnalyticsDate');
    Route::post('/get-visitors-demography-date', [VisitorController::class, 'getVisitorsDemographyDate'])->name('getVisitorsDemographyDate');

    Route::post('/get-visitors-analytics-country', [VisitorController::class, 'getVisitorsAnalyticsCountry'])->name('getVisitorsAnalyticsCountry');
    Route::post('/get-user-summery', [VisitorController::class, 'getUserSummery'])->name('getUserSummery');
    
    Route::get('/admin/dashboard', function () {
        return Inertia::render('Backend/AdminDashboard/Index');
    })->name('admin.dashboard');
    

    Route::get('/admin/dashboard/analytics', [AdminAnalyticsController::class, 'index'])->name('admin.analytics');
    Route::post('/admin/dashboard/analytics', [AdminAnalyticsController::class, 'getTalentEarnings'])->name('admin.getTalentEarnings');

    Route::get('/admin/dashboard/payments', [AdminPaymentController::class, 'payments'])->name('admin.payments');
    Route::get('/admin/dashboard/talent-earnings', [AdminPaymentController::class, 'talentEarnings'])->name('admin.talentEarnings');
    Route::post('/admin/dashboard/pay-talent/{id}', [AdminPaymentController::class, 'talentPay'])->name('admin.talentPay');
    Route::post('/admin/dashboard/cancel-pay-talent/{id}', [AdminPaymentController::class, 'talentPayCancel'])->name('admin.TalentPayCancel');
    
    Route::get('/admin/dashboard/our-celebrities', [TalentController::class, 'ourCelebrities'])->name('admin.ourCelebrities');
    Route::post('/admin/dashboard/get-our-celebrities', [TalentController::class, 'getOurCelebrities'])->name('admin.getOurCelebrities');
    Route::get('/admin/dashboard/talent-applications', [TalentController::class, 'applications'])->name('admin.talent.applications');
    Route::post('/admin/dashboard/approve-talent/{id}', [TalentController::class, 'approve'])->name('talent.approve');


    Route::get('/admin/dashboard/talents', [TalentController::class, 'talents'])->name('admin.talents');
    Route::delete('/admin/dashboard/talents-delete/{id}', [TalentController::class, 'delete'])->name('admin.delete_talent');
    Route::delete('/admin/dashboard/restore-declined-talent/{id}', [TalentController::class, 'restore_declined_talent'])->name('admin.restore_declined_talent');
    Route::post('/admin/dashboard/talents-restore', [TalentController::class, 'restore'])->name('admin.restore');

    Route::get('/admin/dashboard/talent/{user}', [TalentController::class, 'details'])->name('admin.talent.detail');
    Route::get('/admin/dashboard/payment-controls', [TalentController::class, 'BalanceControl'])->name('admin.talent.payment_controls');
    Route::post('/admin/dashboard/{id}/payment-controls-approve', [MailController::class, 'adminBalanceControl'])->name('admin.talent.payment_controls_approve');
    Route::post('/admin/dashboard/{user}/{id}/payment-transfer', [TalentController::class, 'transfer_balance'])->name('admin.talent.transfer_balance');
    Route::post('/admin/dashboard/{user}/{id}/payment-cancel', [TalentController::class, 'cancel_balance'])->name('admin.talent.cancel_balance');

    Route::group(['prefix' => '/admin/dashboard'], function()
    {
        // categories start
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
        Route::post('/category', [CategoryController::class, 'store'])->name('admin.category');
        Route::post('/category/{id}', [CategoryController::class, 'edit'])->name('admin.category.edit');
        Route::delete('/category/delete/{id}', [CategoryController::class, 'delete'])->name('admin.category.delete');
        // categories end

        // occasions start
        Route::get('/occasions', [OcassionController::class, 'index'])->name('admin.ocassions');
        Route::post('/occasion', [OcassionController::class, 'store'])->name('admin.ocassion');
        Route::post('/occasion/{id}', [OcassionController::class, 'edit'])->name('admin.ocassion.edit');
        Route::delete('/occasion/delete/{id}', [OcassionController::class, 'delete'])->name('admin.ocassion.delete');
        // occasions end

        // country start
        Route::get('/countries', [CountryController::class, 'index'])->name('admin.countries');
        Route::post('/country', [CountryController::class, 'store'])->name('admin.country');
        Route::post('/country/{id}', [CountryController::class, 'edit'])->name('admin.country.edit');
        Route::delete('/country/delete/{id}', [CountryController::class, 'delete'])->name('admin.country.delete');
        // country end

        // users start
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        Route::delete('/delete-user/{user}', [UserController::class, 'soft_delete'])->name('admin.delete_user');
        Route::delete('/fource-delete-user/{user}', [UserController::class, 'fource_delete'])->name('admin.fource_delete_user');
        Route::post('/visible-user/{user}', [UserController::class, 'visibility'])->name('admin.visibility');
        Route::delete('/restore-user/{user}', [UserController::class, 'restore_user'])->name('admin.restore_user');
    
        Route::get('/user/{id}', [UserController::class, 'userAdminDetails'])->name('admin.user.detail');
        // users end

        // settings start
        Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/save-settings', [SettingsController::class, 'saveSettings'])->name('admin.saveSettings');
        Route::post('/save-language', [SettingsController::class, 'saveLanguage'])->name('admin.saveLanguage');
        Route::post('/save-page', [SettingsController::class, 'savepage'])->name('admin.savePage');
        Route::delete('/delete-page/{page}', [SettingsController::class, 'deletepage'])->name('admin.deleted.page');
        // settings end

        // contact
        Route::get('/contact', [ContactController::class, 'index'])->name('admin.contact');
        Route::post('/contact/{id}/seen', [ContactController::class, 'seen'])->name('admin.seenContact');
        Route::post('/contact/{contact}/replay', [ContactController::class, 'replay'])->name('admin.replayContact');
        Route::post('/contact/{contact}/delete', [ContactController::class, 'delete'])->name('admin.deleteContact');

        // faq
        Route::post('/faq/store', [FaqController::class, 'store'])->name('admin.saveFaq');
        Route::delete('/faq/delete/{faq}', [FaqController::class, 'delete'])->name('admin.deleteFaq');
        Route::post('/faq/update/{faq}', [FaqController::class, 'update'])->name('admin.updateFaq');

        // home config
        Route::post('/get-filtered-talents', [HomeConfigController::class, 'getFilteredTalents'])->name('getFilteredTalents');
        Route::post('/save-home-talents', [HomeConfigController::class, 'saveHomeTalents'])->name('saveHomeTalents');

        Route::get('/featured-video', [FeaturedVideoController::class, 'index'])->name('admin.featuredVideo');
        Route::post('/save-featured-video', [FeaturedVideoController::class, 'save'])->name('admin.saveFeaturedVideo');
        Route::delete('/delete-featured-video/{id}', [FeaturedVideoController::class, 'delete'])->name('admin.deleteFeaturedVideo');
        Route::post('/delete-featured-video/{id}', [FeaturedVideoController::class, 'status'])->name('admin.statusFeaturedVideo');
    });
});