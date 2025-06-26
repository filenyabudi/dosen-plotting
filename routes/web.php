<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

// add route clear 
Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    return 'Cache cleared successfully!';
});
