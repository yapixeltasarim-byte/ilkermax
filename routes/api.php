<?php

use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\ReferenceController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::get('/categories', [ReferenceController::class, 'categories'])->name('api.categories');
    Route::get('/locations', [ReferenceController::class, 'locations'])->name('api.locations');

    Route::prefix('properties')->name('api.properties.')->group(function () {
        Route::post('/', [PropertyController::class, 'store'])->name('store');
        Route::post('/{property}/images', [PropertyController::class, 'storeImages'])->name('images.store');
    });
});
