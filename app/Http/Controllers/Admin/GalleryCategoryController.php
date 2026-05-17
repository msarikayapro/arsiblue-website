<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\GalleryCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryCategoryController extends Controller
{
    public function index(): View
    {
        $categories = GalleryCategory::ordered()->get();
        $counts = Gallery::selectRaw('category, count(*) as c')->groupBy('category')->pluck('c', 'category')->toArray();

        return view('admin.gallery.categories.index', compact('categories', 'counts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'slug' => ['nullable', 'string', 'alpha_dash', 'max:50', 'unique:gallery_categories,slug'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slug = $data['slug'] ?: Str::slug($data['name'], '_');
        $slug = $this->ensureUniqueSlug($slug);

        GalleryCategory::create([
            'slug' => $slug,
            'name' => $data['name'],
            'sort_order' => $data['sort_order'] ?? (int) GalleryCategory::max('sort_order') + 1,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.gallery.categories.index')
            ->with('success', 'Kategori eklendi.');
    }

    public function update(Request $request, GalleryCategory $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Slug ASLA değiştirilmez — mevcut görsellerin orphan olmaması için
        $category->update([
            'name' => $data['name'],
            'sort_order' => $data['sort_order'] ?? $category->sort_order,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Kategori güncellendi.');
    }

    public function destroy(GalleryCategory $category): RedirectResponse
    {
        $itemCount = Gallery::where('category', $category->slug)->count();
        if ($itemCount > 0) {
            return back()->with('error', "Bu kategoride {$itemCount} görsel var. Önce görselleri silin veya başka kategoriye taşıyın.");
        }

        $category->delete();

        return back()->with('success', 'Kategori silindi.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate(['ids' => ['required', 'array']]);
        foreach ($request->input('ids') as $i => $id) {
            GalleryCategory::where('id', $id)->update(['sort_order' => $i + 1]);
        }

        return response()->json(['ok' => true]);
    }

    private function ensureUniqueSlug(string $slug): string
    {
        $base = $slug;
        $i = 2;
        while (GalleryCategory::where('slug', $slug)->exists()) {
            $slug = $base.'_'.$i++;
        }

        return $slug;
    }
}
