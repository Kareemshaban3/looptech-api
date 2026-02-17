<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PackageController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WorkController;

Route::apiResource('projects', ProjectController::class);
Route::apiResource('packages', PackageController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('works', WorkController::class);
Route::post('/packages/{package}/feature', [PackageController::class, 'addFeature']);