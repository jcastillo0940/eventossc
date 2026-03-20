<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\EventSettingController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\ParticipantProfileController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/profile', [ParticipantProfileController::class, 'show'])->middleware('auth:sanctum');

Route::prefix('admin')->group(function () {
    Route::apiResource('events', EventController::class);
    Route::post('events/{event}/brands', [BrandController::class, 'store']);
    Route::post('events/{event}/settings', [EventSettingController::class, 'update']);
    Route::apiResource('participants', ParticipantController::class);
});
