<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\BookingController;

Route::get('/calendar', [CalendarController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
