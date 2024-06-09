<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JaktfuglerController;

Route::get('/', [JaktfuglerController::class, 'index']);
Route::post('/sjekk', [JaktfuglerController::class, 'sjekk']);
Route::post('/liste', [JaktfuglerController::class, 'liste']);