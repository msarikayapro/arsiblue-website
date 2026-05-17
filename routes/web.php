<?php

use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\GalleryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\LandingController;
use App\Http\Controllers\Site\LeadController;
use App\Http\Controllers\Site\LegalController;
use App\Http\Controllers\Site\PageController;
use App\Http\Controllers\Site\SitemapController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/bayrama-ozel', [LandingController::class, 'bayrama'])->name('landing.bayrama');
Route::get('/balayi-paketi', [LandingController::class, 'balayi'])->name('landing.balayi');
Route::get('/aile-oteli', [LandingController::class, 'aile'])->name('landing.aile');

Route::get('/odalar', [PageController::class, 'rooms'])->name('rooms');
Route::get('/tesisler', [PageController::class, 'facilities'])->name('facilities');

Route::get('/galeri', [GalleryController::class, 'index'])->name('gallery');
Route::get('/iletisim', [ContactController::class, 'index'])->name('contact');

Route::post('/bilgi-al', [LeadController::class, 'store'])->name('lead.store');

Route::get('/kvkk', [LegalController::class, 'kvkk'])->name('legal.kvkk');
Route::get('/cerez-politikasi', [LegalController::class, 'cerez'])->name('legal.cerez');
Route::get('/hakkimizda', [LegalController::class, 'about'])->name('legal.about');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// TEMP: tek seferlik kurulum route'u — kullandıktan SONRA bu blok silinecek
Route::get('/__setup-x9k2m7p4q8r3v6n1', function () {
    $out = [];

    // DB'deki tüm tabloları drop'la ve sıfırdan migrate + seed çalıştır
    Artisan::call('migrate:fresh', ['--force' => true, '--seed' => true]);
    $out[] = "MIGRATE:FRESH + SEED:\n" . Artisan::output();

    Artisan::call('storage:link');
    $out[] = "STORAGE LINK:\n" . Artisan::output();

    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');
    $out[] = "CACHE REBUILT";

    return '<pre>' . e(implode("\n\n", $out)) . '</pre>';
});

require __DIR__.'/admin.php';
