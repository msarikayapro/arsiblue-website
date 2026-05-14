<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $items = Gallery::active()->orderBy('category')->orderBy('sort_order')->get()->groupBy('category');

        return view('site.gallery', compact('items'));
    }
}
