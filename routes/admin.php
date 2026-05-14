<?php

use App\Http\Controllers\Admin\AgencyController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventLogController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\TrackingController;
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

        // Kampanyalar (Adım 6)
        Route::get('campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('campaigns/create', [CampaignController::class, 'create'])->name('campaigns.create');
        Route::post('campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
        Route::get('campaigns/{campaign}/edit', [CampaignController::class, 'edit'])->name('campaigns.edit');
        Route::put('campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
        Route::delete('campaigns/{campaign}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');
        Route::post('campaigns/{campaign}/toggle-active', [CampaignController::class, 'toggleActive'])->name('campaigns.toggle-active');
        Route::post('campaigns/{campaign}/upload-image', [CampaignController::class, 'uploadImage'])->name('campaigns.upload-image');

        // Tracking & Pixel (Adım 7)
        Route::get('tracking', [TrackingController::class, 'index'])->name('tracking.index');
        Route::put('tracking/meta', [TrackingController::class, 'updateMeta'])->name('tracking.meta');
        Route::put('tracking/google', [TrackingController::class, 'updateGoogle'])->name('tracking.google');
        Route::put('tracking/tiktok', [TrackingController::class, 'updateTiktok'])->name('tracking.tiktok');
        Route::post('tracking/test-capi', [TrackingController::class, 'testCapi'])->name('tracking.test-capi');
        Route::get('tracking/health', [TrackingController::class, 'health'])->name('tracking.health');

        // ===== Adım 8 — kalan modüller =====

        // Odalar
        Route::resource('rooms', RoomController::class)->except('show');
        Route::post('rooms/{room}/upload-image', [RoomController::class, 'uploadImage'])->name('rooms.upload-image');

        // Galeri
        Route::get('gallery', [GalleryController::class, 'index'])->name('gallery.index');
        Route::post('gallery/upload', [GalleryController::class, 'upload'])->name('gallery.upload');
        Route::put('gallery/{gallery}', [GalleryController::class, 'update'])->name('gallery.update');
        Route::delete('gallery/{gallery}', [GalleryController::class, 'destroy'])->name('gallery.destroy');
        Route::post('gallery/reorder', [GalleryController::class, 'reorder'])->name('gallery.reorder');

        // SSS
        Route::resource('faqs', FaqController::class)->except('show');

        // SEO
        Route::get('seo', [SeoController::class, 'index'])->name('seo.index');
        Route::get('seo/{slug}', [SeoController::class, 'edit'])->name('seo.edit');
        Route::put('seo/{slug}', [SeoController::class, 'update'])->name('seo.update');

        // Event Logları
        Route::get('events', [EventLogController::class, 'index'])->name('events.index');
        Route::get('events/export', [EventLogController::class, 'export'])->name('events.export');
        Route::get('events/{event}', [EventLogController::class, 'show'])->name('events.show');

        // Lead'ler
        Route::get('leads', [LeadController::class, 'index'])->name('leads.index');
        Route::get('leads/{lead}', [LeadController::class, 'show'])->name('leads.show');
        Route::put('leads/{lead}/status', [LeadController::class, 'updateStatus'])->name('leads.status');
        Route::put('leads/{lead}/notes', [LeadController::class, 'updateNotes'])->name('leads.notes');
        Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy');

        // İletişim
        Route::get('contact', [ContactController::class, 'edit'])->name('contact.edit');
        Route::put('contact', [ContactController::class, 'update'])->name('contact.update');

        // Acenta
        Route::get('agency', [AgencyController::class, 'edit'])->name('agency.edit');
        Route::put('agency', [AgencyController::class, 'update'])->name('agency.update');
        Route::post('agency/tursab-upload', [AgencyController::class, 'uploadTursab'])->name('agency.tursab-upload');

        // Genel Ayarlar
        Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

        // Gizli system update rotası — .env'deki SYSTEM_UPDATE_SECRET ile maskelenir
        // (cPanel SSH erişimi sınırlı olduğu için web üzerinden migration tetiklemek)
        if ($secret = env('SYSTEM_UPDATE_SECRET')) {
            Route::get('system-update-'.$secret, [SystemController::class, 'migrate'])
                ->name('system.update');
        }
    });
});
