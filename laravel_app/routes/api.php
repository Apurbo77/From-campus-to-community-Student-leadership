<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\ApiController;

Route::get('/students', [ApiController::class, 'students']);
Route::get('/camps', [ApiController::class, 'camps']);
Route::get('/requests', [ApiController::class, 'requests']);
Route::get('/training', [ApiController::class, 'training']);
