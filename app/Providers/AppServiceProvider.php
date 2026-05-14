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
