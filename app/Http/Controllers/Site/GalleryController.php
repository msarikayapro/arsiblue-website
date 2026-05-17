<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        // Sadece aktif kategorileri sırasıyla al → slug => name eşlemesi
        $categories = GalleryCategory::active()->ordered()->pluck('name', 'slug');

        // Yalnızca aktif kategorilerdeki aktif görseller
        $items = Gallery::active()
            ->whereIn('category', $categories->keys())
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');

        return view('site.gallery', compact('items', 'categories'));
    }
}
