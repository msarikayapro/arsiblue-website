<?php

use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\GalleryController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\LandingController;
use App\Http\Controllers\Site\LeadController;
use App\Http\Controllers\Site\LegalController;
use App\Http\Controllers\Site\PageController;
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

require __DIR__.'/admin.php';
