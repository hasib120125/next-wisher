<?php

use App\Http\Controllers\Backend\ContactController;
use App\Http\Controllers\Backend\VisitorController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;


Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/pages/{slug}', [FrontendController::class, 'pages'])->name('pages');

Route::get('/contact', [FrontendController::class, 'contact'])->name('contact');
Route::post('/contact/store', [ContactController::class, 'storeContact'])->name('contact.store');

Route::get('/faq', [FrontendController::class, 'faq'])->name('faq');

Route::get('/categories', [FrontendController::class, 'categories'])->name('categories');

Route::post('/visit', [VisitorController::class, 'visit'])->name('visit');

Route::get('/media/{path}', [PostController::class, 'getVideo']);

Route::post('/search', [FrontendController::class, 'search'])->name('search');

Route::get('/hire-celebrities', [FrontendController::class, 'hireCelebrities'])->name('hireCelebrities');

Route::post('/save-hire', [FrontendController::class, 'saveHire'])->name('saveHire');
