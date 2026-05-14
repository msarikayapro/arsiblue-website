<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        $pages = Page::orderBy('sort_order')->withCount('contents')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function edit(string $slug): View
    {
        $page = Page::bySlug($slug)->firstOrFail();
        $page->load(['contents' => fn ($q) => $q->orderBy('sort_order')]);

        return view('admin.pages.edit', compact('page'));
    }

    /**
     * Tüm formdan toplu kaydetme (manuel "Kaydet" butonu).
     */
    public function update(Request $request, string $slug): RedirectResponse
    {
        $page = Page::bySlug($slug)->firstOrFail();

        $sections = $request->input('sections', []);

        foreach ($sections as $sectionId => $value) {
            $section = PageContent::where('page_id', $page->id)->where('id', $sectionId)->first();
            if (! $section) {
                continue;
            }

            $this->saveSectionValue($section, $value);
        }

        $this->forgetPageCache($page->slug);

        return redirect()->route('admin.pages.edit', $page->slug)
            ->with('success', 'Sayfa içerikleri kaydedildi.');
    }

    /**
     * Tek section AJAX auto-save endpoint'i.
     */
    public function updateSection(Request $request, string $slug, int $sectionId): JsonResponse
    {
        $page = Page::bySlug($slug)->firstOrFail();
        $section = PageContent::where('page_id', $page->id)->findOrFail($sectionId);

        $value = $request->input('value');

        try {
            $this->saveSectionValue($section, $value);
        } catch (\JsonException $e) {
            return response()->json([
                'ok' => false,
                'error' => 'JSON formatı hatalı: '.$e->getMessage(),
            ], 422);
        }

        $this->forgetPageCache($page->slug);

        return response()->json([
            'ok' => true,
            'saved_at' => now()->format('H:i:s'),
        ]);
    }

    public function uploadImage(Request $request, string $slug): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'], // 4MB
        ]);

        $page = Page::bySlug($slug)->firstOrFail();

        $file = $request->file('image');
        $filename = Str::slug($page->slug).'-'.time().'-'.Str::random(6).'.'.$file->extension();

        $file->move(public_path('storage/uploads/pages'), $filename);

        return response()->json([
            'ok' => true,
            'filename' => $filename,
            'url' => asset('storage/uploads/pages/'.$filename),
        ]);
    }

    /**
     * Section'a değer atar; type-aware validasyon yapar.
     */
    private function saveSectionValue(PageContent $section, mixed $value): void
    {
        if ($section->content_type === 'json' && is_string($value) && $value !== '') {
            // JSON validate (atar exception)
            json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        }

        $section->content = $value === null ? null : (string) $value;
        $section->save();
    }

    private function forgetPageCache(string $slug): void
    {
        Cache::forget("page.{$slug}");
    }
}
