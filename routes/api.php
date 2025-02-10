<?php

use App\Http\Controllers\Backend\VisitorController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\fileUploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\Category;
use \App\Models\Country;
use App\Models\Language;
use App\Models\Page;
use App\Models\Settings;
use App\Models\Rating;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/categories', function(){
    $categories = Category::where(['status' => 1])->orderBy('name', 'asc')->get();
    return response()->json($categories);
});

Route::get('/countries', function(){
    $countries = Country::where(['status' => 1])->orderBy('name', 'asc')->get();
    return response()->json($countries);
});

Route::get('/languages', function(){
    $languages = Language::get();
    return response()->json($languages);
});

Route::get('/settings', function(){
    $settings = Settings::first();
    return response()->json($settings);
});

Route::get('/ratings', function(Request $request){
    $talentId = $request->talentId;
    $userId   = $request->userId;
    $talentEarningId   = $request->talentEarningId;
    // $ratings  = Rating::where(function ($query) use ($talentId, $userId) {
    //     $query->where('talent_id', $talentId)
    //         ->where('user_id', $userId);
    // })
    // ->orWhere(function ($query) use ($talentId, $userId) {
    //     $query->where('talent_id', $talentId)
    //         ->orWhere('user_id', $userId);
    // })
    // ->get();
    $ratings = [];
    $query = Rating::query();
    if ($talentEarningId) {
        $query->where('talent_earning_id', $talentEarningId);
    }
    if($talentId && $userId) {
        $ratings = $query->where('talent_id', $talentId)
                            ->where('user_id', $userId)
                            ->get();
    }
    if($talentId && !$userId) {
        $ratings = Rating::where('talent_id', $talentId)->get();
    }

    return response()->json($ratings);
});

Route::post('/ratings', function(Request $request){
    $res = Rating::create($request->all());
    return response()->json($res);
});


Route::get('/pages', function(){
    $type = request()->get('type');
    $query = Page::query();
    if ($type) {
        $query->where('type', $type);
    } else {
        $query->whereIn('type', ['privacy-policy', 'terms-of-service']);
    }
    $pages = $query->get();
    return response()->json($pages);
});



Route::get('media/{id}', [fileUploadController::class, 'media']);
Route::delete('media/delete/{id}/{user_id}', [fileUploadController::class, 'mediaDelete']);
Route::post('/edit-calendar/{id}/{user_id}', [CalendarController::class, 'update'])->name('calendar_update');