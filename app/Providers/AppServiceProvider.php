<?php

namespace App\Providers;

use App\View\Composers\GlobalDataComposer;
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
    }
}
