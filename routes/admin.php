<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {

    // Public auth routes (guest only)
    Route::middleware('guest')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->name('login.attempt');
    });

    // Authenticated admin routes
    Route::middleware('admin.auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index']);

        // Sayfa İçerikleri (Adım 5)
        Route::get('pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('pages/{slug}', [PageController::class, 'edit'])->name('pages.edit');
        Route::put('pages/{slug}', [PageController::class, 'update'])->name('pages.update');
        Route::post('pages/{slug}/sections/{section}', [PageController::class, 'updateSection'])
            ->name('pages.section.update');
        Route::post('pages/{slug}/upload-image', [PageController::class, 'uploadImage'])
            ->name('pages.upload-image');

        // Aşağıdaki modüller Adım 6-8 ile gelecek
        // Route::resource('campaigns', CampaignController::class);
        // Route::get('tracking', ...);
        // ...
    });
});
