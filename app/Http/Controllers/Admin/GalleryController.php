<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public const CATEGORIES = ['havuz', 'plaj', 'oda', 'yemek', 'animasyon', 'dis_mekan'];

    public function index(Request $request): View
    {
        $query = Gallery::orderBy('category')->orderBy('sort_order');
        if ($cat = $request->query('category')) {
            $query->where('category', $cat);
        }
        $items = $query->paginate(60)->withQueryString();
        $counts = Gallery::selectRaw('category, count(*) as c')->groupBy('category')->pluck('c', 'category')->toArray();

        return view('admin.gallery.index', compact('items', 'counts'));
    }

    /**
     * Bulk upload — birden fazla dosya, tek kategori.
     */
    public function upload(Request $request): RedirectResponse
    {
        $request->validate([
            'category' => ['required', 'in:'.implode(',', self::CATEGORIES)],
            'images' => ['required', 'array', 'min:1'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:6144'],
        ]);

        $maxOrder = (int) Gallery::where('category', $request->input('category'))->max('sort_order');

        foreach ($request->file('images', []) as $i => $file) {
            $name = $request->input('category').'-'.time().'-'.($i + 1).'-'.Str::random(5).'.'.$file->extension();
            $file->move(public_path('storage/uploads/gallery'), $name);

            Gallery::create([
                'category' => $request->input('category'),
                'image_path' => $name,
                'thumbnail_path' => $name, // Adım 14'te resize ile gerçek thumb
                'alt_text' => '',
                'sort_order' => $maxOrder + $i + 1,
                'is_active' => true,
            ]);
        }

        return back()->with('success', count($request->file('images', [])).' görsel yüklendi.');
    }

    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $data = $request->validate([
            'alt_text' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'category' => ['nullable', 'in:'.implode(',', self::CATEGORIES)],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        $gallery->update($data);

        return back()->with('success', 'Görsel güncellendi.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $path = public_path('storage/uploads/gallery/'.$gallery->image_path);
        if (file_exists($path)) {
            @unlink($path);
        }
        $gallery->delete();

        return back()->with('success', 'Görsel silindi.');
    }

    /**
     * Drag-drop reorder — id sırasıyla sort_order atar.
     * Frontend JS'ten POST: { ids: [12, 5, 8, ...] }
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate(['ids' => ['required', 'array']]);
        foreach ($request->input('ids') as $i => $id) {
            Gallery::where('id', $id)->update(['sort_order' => $i + 1]);
        }

        return response()->json(['ok' => true]);
    }
}
