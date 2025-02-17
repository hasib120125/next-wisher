<?php
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\fileUploadController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Calendar;
use Illuminate\Http\Request;


Route::middleware(['auth', 'user-role:talent', 'verified'])->group(function(){
    Route::get('/talent/calendar/preview/{id}', function (Request $request, $id) {
        $calendar = Calendar::where(['user_id' => $request->user()->id])
                                ->where(['id' => $id])
                                ->first();
                                
        return Inertia::render('Calendar/Preview', ['calendar' => $calendar]);
    })->name('calendar.preview');

    Route::get('/talent/calendar', function () {
        return redirect()->route('calendar');
    })->name('calendar');

    Route::get('/talent/calendar/create', function () {
        return Inertia::render('Calendar/Create');
    })->name('calendar');

    Route::get('/talent/calendar/edit/{id?}', function ($id)
    {
        $data = Calendar::where(['id' => $id])->first();
        return Inertia::render('Calendar/edit', ['data' => $data]);
    })->name('calendar.edit');

    Route::get('/talent/calendars', [CalendarController::class, 'index'])->name('my_calendars');

    Route::post('/save-calendar', [CalendarController::class, 'store']) ->name('calendar_save');
    Route::post('/edit-calendar/{id}/{user_id}', [CalendarController::class, 'update'])->name('calendar_update');
    Route::post('/talent/edit-calendar-price/{id}/{user_id}', [CalendarController::class, 'priceUpdate'])->name('calendar_price_update');
    Route::delete('/talent/delete/{calendar_id}', [CalendarController::class, 'delete'])->name('calendar_delete');
    Route::post('/talent/salable/{calendar_id}', [CalendarController::class, 'makeCalendarSalable'])->name('salable');
    Route::post('/file-upload', [fileUploadController::class, 'fileUpload'])->name('file_upload');
});