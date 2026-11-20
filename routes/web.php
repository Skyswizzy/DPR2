<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// The inventory is the home page. Guests are sent on to the login page by the "auth" middleware.
Route::redirect('/', '/equipment');

// Public: login only (there is no registration).
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');

// Administrator pages: everything below requires a signed-in user.
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // index, create, store, show, edit, update, destroy
    Route::resource('equipment', EquipmentController::class);

    Route::prefix('reports')
        ->name('reports.')
        ->controller(ReportController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/export/pdf', 'pdf')->name('pdf');
            Route::get('/export/excel', 'excel')->name('excel');
        });
});
