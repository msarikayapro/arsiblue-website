<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\PageContent;
use App\Models\Room;
use App\Models\Setting;
use App\View\Composers\GlobalDataComposer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Cloudflare arkasında flexible SSL ile origin'e HTTP gelse bile
        // tüm asset/route/url helper'ları https:// üretsin (mixed content fix).
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Eski MySQL/MariaDB sürümlerinde utf8mb4 + 255 char index = 1020 byte > 1000 byte limit.
        // 191 → 191×4 = 764 byte, güvenli sınır.
        Schema::defaultStringLength(191);

        // Tüm site/* view'larına $seo, $schemaType, $schemaData inject et
        View::composer(['layouts.site', 'site.*'], GlobalDataComposer::class);

        // Cache invalidation — bu modeller değiştiğinde tüm cache'i flush et.
        // (file/database cache driver pattern-based forget desteklemiyor;
        // küçük site için flush kabul edilebilir maliyet)
        $cacheableModels = [Page::class, PageContent::class, Campaign::class, Room::class, Faq::class, Gallery::class, Setting::class];
        foreach ($cacheableModels as $model) {
            $model::saved(fn () => Cache::flush());
            $model::deleted(fn () => Cache::flush());
        }
    }
}
