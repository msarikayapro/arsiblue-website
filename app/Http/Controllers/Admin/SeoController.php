<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\SeoMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function index(): View
    {
        $pages = Page::orderBy('sort_order')->get()->map(function ($p) {
            $p->seo = SeoMeta::forPage($p->slug);

            return $p;
        });

        return view('admin.seo.index', compact('pages'));
    }

    public function edit(string $slug): View
    {
        $page = Page::bySlug($slug)->firstOrFail();
        $seo = SeoMeta::firstOrNew(['page_slug' => $slug], ['robots' => 'index,follow']);

        return view('admin.seo.edit', compact('page', 'seo'));
    }

    public function update(Request $request, string $slug): RedirectResponse
    {
        Page::bySlug($slug)->firstOrFail();

        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:70'],
            'description' => ['nullable', 'string', 'max:200'],
            'keywords' => ['nullable', 'string', 'max:255'],
            'og_title' => ['nullable', 'string', 'max:70'],
            'og_description' => ['nullable', 'string', 'max:200'],
            'og_image' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:255'],
            'robots' => ['nullable', 'string', 'max:50'],
            'schema_json' => ['nullable', 'string', 'max:10000'],
        ]);

        if (! empty($data['schema_json'])) {
            try {
                json_decode($data['schema_json'], true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                return back()->withInput()->withErrors(['schema_json' => 'JSON formatı hatalı: '.$e->getMessage()]);
            }
        }

        SeoMeta::updateOrCreate(['page_slug' => $slug], $data);

        return back()->with('success', 'SEO ayarları kaydedildi.');
    }
}
