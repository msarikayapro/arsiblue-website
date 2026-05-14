<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Faq;
use App\Models\Page;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function bayrama(): View
    {
        return $this->render('bayrama-ozel');
    }

    public function balayi(): View
    {
        return $this->render('balayi-paketi');
    }

    public function aile(): View
    {
        return $this->render('aile-oteli');
    }

    private function render(string $slug): View
    {
        $page = Page::bySlug($slug)->with(['contents' => fn ($q) => $q->orderBy('sort_order')])->first();

        $campaign = Campaign::active()
            ->where(function ($q) use ($slug) {
                $q->whereJsonContains('visible_landings', $slug);
            })
            ->orderBy('sort_order')
            ->first()
            ?? Campaign::active()->orderBy('sort_order')->first();

        $faqs = Faq::active()->forPage($slug)->orderBy('sort_order')->limit(5)->get();

        return view("site.landing.{$slug}", [
            'page' => $page,
            'campaign' => $campaign,
            'faqs' => $faqs,
            'schemaData' => ['faqs' => $faqs, 'breadcrumbs' => [
                ['name' => 'Ana Sayfa', 'url' => url('/')],
                ['name' => $page->title, 'url' => url('/'.$slug)],
            ]],
        ]);
    }
}
