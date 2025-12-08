<?php

use App\Http\Controllers\EventController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/events', [EventController::class, 'getEventsWithWorkshops']);
Route::get('/futureevents', [EventController::class, 'getFutureEventsWithWorkshops']);
Route::get('/warmupevents', [EventController::class, 'getWarmupEvents']);
Route::get('/menu', [MenuController::class, 'getMenuItems']);

Route::get('/orders', [OrderController::class, 'index']);
