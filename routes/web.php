<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeatureController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\IndustryController;
use App\Http\Controllers\PageController;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Dari unless the browser explicitly asks for another language we offer.
    $preferred = request()->hasHeader('Accept-Language')
        ? request()->getPreferredLanguage(array_keys(config('site.locales')))
        : null;

    return redirect()->route('home', ['locale' => $preferred ?: config('site.default_locale')]);
});

Route::get('/sitemap.xml', [PageController::class, 'sitemap'])->name('sitemap');

Route::prefix('{locale}')->middleware(SetLocale::class)->group(function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/pricing', [PageController::class, 'pricing'])->name('pricing');
    Route::get('/faq', [PageController::class, 'faq'])->name('faq');
    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/security', [PageController::class, 'security'])->name('security');

    Route::get('/features', [FeatureController::class, 'index'])->name('features.index');
    Route::get('/features/{feature:slug}', [FeatureController::class, 'show'])->name('features.show');

    Route::get('/industries', [IndustryController::class, 'index'])->name('industries.index');
    Route::get('/industries/{industry:slug}', [IndustryController::class, 'show'])->name('industries.show');

    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
    Route::get('/help/search', [HelpController::class, 'search'])->name('help.search');
    Route::get('/help/{category:slug}', [HelpController::class, 'category'])->name('help.category');
    Route::get('/help/{category:slug}/{article:slug}', [HelpController::class, 'article'])->name('help.article');
    Route::post('/help/article/{article}/feedback', [HelpController::class, 'feedback'])->name('help.feedback')->middleware('throttle:10,1');

    Route::get('/contact', [ContactController::class, 'show'])->name('contact');
    Route::get('/demo', [ContactController::class, 'demo'])->name('demo');
    Route::post('/contact', [ContactController::class, 'storeMessage'])->name('contact.store')->middleware('throttle:5,1');
    Route::post('/demo', [ContactController::class, 'storeDemo'])->name('demo.store')->middleware('throttle:5,1');
});
