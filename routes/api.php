<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('banners')->name('api.banners.')->group(function () {
    Route::get('{uuid}/embed', [\App\Http\Controllers\Api\BannerEmbedController::class, 'embed'])->name('embed');
    Route::get('{uuid}', [\App\Http\Controllers\Api\BannerEmbedController::class, 'show'])->name('show');
});

// Placement-based banner API
Route::prefix('placements')->name('api.placements.')->group(function () {
    Route::post('banners', [\App\Http\Controllers\Api\PlacementController::class, 'getBanners'])->name('banners');
    Route::post('track/impression', [\App\Http\Controllers\Api\PlacementController::class, 'trackImpression'])->name('track.impression');
    Route::post('track/click', [\App\Http\Controllers\Api\PlacementController::class, 'trackClick'])->name('track.click');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
