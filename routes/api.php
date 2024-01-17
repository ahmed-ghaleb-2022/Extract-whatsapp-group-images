<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\extractController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/groups', [extractController::class, 'index']);
Route::get('/fetch-url', [extractController::class, 'fetchUrl']);
Route::post('/add-group', [extractController::class, 'addGroup']);
Route::delete('/delete-group/{id}', [extractController::class, 'destroy']);