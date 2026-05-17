<?php

use App\Http\Controllers\Api\TrackingController;
use Illuminate\Support\Facades\Route;

// web middleware gerekli: session (UTM/landing_page) + CSRF token doğrulama.
// API prefix'inde olmasına rağmen frontend'den same-origin AJAX olarak çağrılıyor.
Route::middleware('web')
    ->post('/track-event', [TrackingController::class, 'trackEvent'])
    ->name('api.track-event');
