<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Room;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $page = Page::bySlug('home')->with(['contents' => fn ($q) => $q->orderBy('sort_order')])->first();

        $campaign = Campaign::active()->featuredOnHomepage()->orderBy('sort_order')->first();
        $rooms = Room::active()->orderBy('sort_order')->limit(3)->get();
        $faqs = Faq::active()->forPage('home')->orderBy('sort_order')->limit(8)->get();

        return view('site.home', [
            'page' => $page,
            'campaign' => $campaign,
            'rooms' => $rooms,
            'faqs' => $faqs,
            'schemaType' => 'Hotel',
            'schemaData' => ['faqs' => $faqs, 'breadcrumbs' => [
                ['name' => 'Ana Sayfa', 'url' => url('/')],
            ]],
        ]);
    }
}
