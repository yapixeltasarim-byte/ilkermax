<?php

use App\Http\Controllers\Api\PropertyController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->prefix('properties')->name('api.properties.')->group(function () {
    Route::post('/', [PropertyController::class, 'store'])->name('store');
    Route::post('/{property}/images', [PropertyController::class, 'storeImages'])->name('images.store');
});
