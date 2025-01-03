<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::resource('dosens', App\Http\Controllers\DosenController::class)->except('destroy');

Route::resource('matakuliahs', App\Http\Controllers\MatakuliahController::class)->except('destroy');

Route::resource('plottings', App\Http\Controllers\PlottingController::class)->except('destroy');
