<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    public const CATEGORIES = ['genel', 'oda', 'odeme', 'aile'];

    public function index(): View
    {
        $faqs = Faq::orderBy('category')->orderBy('sort_order')->get();

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create(): View
    {
        return view('admin.faqs.edit', [
            'faq' => new Faq(['category' => 'genel', 'is_active' => true, 'sort_order' => 0]),
            'isNew' => true,
            'pageSlugs' => Page::active()->orderBy('sort_order')->pluck('title', 'slug')->toArray(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Faq::create($this->validateData($request));

        return redirect()->route('admin.faqs.index')->with('success', 'SSS eklendi.');
    }

    public function edit(Faq $faq): View
    {
        return view('admin.faqs.edit', [
            'faq' => $faq,
            'isNew' => false,
            'pageSlugs' => Page::active()->orderBy('sort_order')->pluck('title', 'slug')->toArray(),
        ]);
    }

    public function update(Request $request, Faq $faq): RedirectResponse
    {
        $faq->update($this->validateData($request));

        return redirect()->route('admin.faqs.index')->with('success', 'SSS güncellendi.');
    }

    public function destroy(Faq $faq): RedirectResponse
    {
        $faq->delete();

        return back()->with('success', 'SSS silindi.');
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'question' => ['required', 'string', 'max:500'],
            'answer' => ['required', 'string', 'max:5000'],
            'category' => ['required', 'in:'.implode(',', self::CATEGORIES)],
            'visible_pages' => ['nullable', 'array'],
            'visible_pages.*' => ['string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        // Boş array ve "tüm sayfalar" durumu null demektir
        if (empty($data['visible_pages'])) {
            $data['visible_pages'] = null;
        }

        return $data;
    }
}
