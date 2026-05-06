<?php

use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\DealershipController;
use App\Http\Controllers\Public\ReviewController;
use App\Http\Controllers\Public\DashboardController;
use App\Http\Controllers\Public\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/robots.txt', [SitemapController::class, 'robots']);
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dealerships', [DealershipController::class, 'index'])->name('dealerships.index');
Route::get('/dealerships/{dealership}', [DealershipController::class, 'show'])->name('dealerships.show');

Route::get('/news', [NewsController::class, 'index'])->name('news.index');
Route::get('/news/{news}', [NewsController::class, 'show'])->name('news.show');

Route::get('/about', function () {
    return view('public.about');
})->name('about');

Route::get('/contacts', function () {
    return view('public.contacts');
})->name('contacts');

Route::post('/dealerships/{dealership}/reviews', [ReviewController::class, 'store'])
    ->middleware('throttle:3,1')
    ->name('reviews.store');

Route::middleware(['auth'])->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/reviews', [DashboardController::class, 'reviews'])->name('reviews');
        Route::get('/favorites', [DashboardController::class, 'favorites'])->name('favorites');
    });

    Route::post('/dealerships/{dealership}/favorite', [\App\Http\Controllers\Public\FavoriteController::class, 'toggle'])->name('dealerships.favorite');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
