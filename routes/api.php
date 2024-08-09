<?php

use App\Http\Controllers\DetectionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/detection1' , [DetectionController::class  , 'createDetectionMessage1']);
Route::post('/detection2' , [DetectionController::class  , 'createDetectionMessage2']);
Route::post('/detection3' , [DetectionController::class  , 'createDetectionMessage3']);
Route::post('/detection4' , [DetectionController::class  , 'createDetectionMessage4']);

