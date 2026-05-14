<?php

namespace App\View\Composers;

use App\Models\SeoMeta;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;

class GlobalDataComposer
{
    /**
     * Tüm site view'larına ortak veri inject eder:
     * - $seo: SeoMeta veya null (mevcut sayfa için)
     * - $schemaType: WebPage | Hotel | LocalBusiness | FAQPage
     * - $schemaData: schema service'e geçecek payload
     */
    public function compose(View $view): void
    {
        $routeName = Route::currentRouteName();
        $slug = $this->routeNameToSlug($routeName);

        $view->with('seo', SeoMeta::forPage($slug));

        if (! $view->offsetExists('schemaType')) {
            $view->with('schemaType', 'WebPage');
        }
        if (! $view->offsetExists('schemaData')) {
            $view->with('schemaData', []);
        }
    }

    private function routeNameToSlug(?string $routeName): string
    {
        return match ($routeName) {
            'home' => 'home',
            'landing.bayrama' => 'bayrama-ozel',
            'landing.balayi' => 'balayi-paketi',
            'landing.aile' => 'aile-oteli',
            'rooms' => 'odalar',
            'facilities' => 'tesisler',
            'gallery' => 'galeri',
            'contact' => 'iletisim',
            'legal.kvkk' => 'kvkk',
            'legal.cerez' => 'cerez',
            'legal.about' => 'hakkimizda',
            default => 'home',
        };
    }
}
