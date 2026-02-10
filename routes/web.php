<?php

use App\Http\Controllers\BannerController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('instructions', function () {
        return Inertia::render('Instructions', [
            'scriptUrl' => config('services.sponsor.cdn_url') ?: asset('js/sponsor-ads.min.js'),
        ]);
    })->name('instructions');

    Route::resource('banners', BannerController::class);
});

require __DIR__.'/settings.php';
