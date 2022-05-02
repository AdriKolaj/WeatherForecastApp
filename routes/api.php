<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WeatherController;



Route::namespace('Api')->group(function(){
    Route::get('/weather/{date}', [WeatherController::class, 'getWeather'])->name('get.weather');
});