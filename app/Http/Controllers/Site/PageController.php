<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Room;
use Illuminate\View\View;

class PageController extends Controller
{
    public function rooms(): View
    {
        $page = Page::bySlug('odalar')->with('contents')->first();
        $rooms = Room::active()->orderBy('sort_order')->get();

        return view('site.rooms', compact('page', 'rooms'));
    }

    public function facilities(): View
    {
        $page = Page::bySlug('tesisler')->with('contents')->first();

        return view('site.facilities', compact('page'));
    }
}
