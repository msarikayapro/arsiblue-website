<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Response;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = Sitemap::create();

        $sitemap->add(Url::create(url('/'))->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));

        // Tüm aktif page'ler
        Page::active()->orderBy('sort_order')->get()->each(function (Page $page) use ($sitemap) {
            if ($page->slug === 'home') {
                return; // / zaten eklendi
            }
            $url = $page->slug === 'cerez' ? '/cerez-politikasi' : '/'.$page->slug;
            $priority = match ($page->template) {
                'landing' => 0.9,
                'home' => 1.0,
                'legal' => 0.3,
                default => 0.7,
            };
            $sitemap->add(Url::create(url($url))
                ->setPriority($priority)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setLastModificationDate($page->updated_at));
        });

        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }

    public function robots(): Response
    {
        $body = "User-agent: *\n";
        $body .= "Disallow: /admin\n";
        $body .= "Disallow: /api\n";
        $body .= "Allow: /\n\n";
        $body .= 'Sitemap: '.url('/sitemap.xml')."\n";

        return response($body, 200, ['Content-Type' => 'text/plain']);
    }
}
