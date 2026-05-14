<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CampaignController extends Controller
{
    /** Spec'te yer alan landing slug'ları — visible_landings checkbox'ları için */
    public const LANDINGS = [
        'bayrama-ozel' => 'Bayrama Özel',
        'balayi-paketi' => 'Balayı Paketi',
        'aile-oteli' => 'Aile Oteli',
    ];

    public function index(): View
    {
        $campaigns = Campaign::orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        $campaign = new Campaign(['currency' => 'TL', 'is_active' => false, 'show_on_homepage' => false]);

        return view('admin.campaigns.edit', [
            'campaign' => $campaign,
            'isNew' => true,
            'landings' => self::LANDINGS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $campaign = Campaign::create($data);

        Cache::forget('campaign.active');

        return redirect()->route('admin.campaigns.edit', $campaign)
            ->with('success', 'Kampanya oluşturuldu.');
    }

    public function edit(Campaign $campaign): View
    {
        return view('admin.campaigns.edit', [
            'campaign' => $campaign,
            'isNew' => false,
            'landings' => self::LANDINGS,
        ]);
    }

    /**
     * Form submit (manuel) ya da auto-save AJAX (Accept: application/json).
     */
    public function update(Request $request, Campaign $campaign): RedirectResponse|JsonResponse
    {
        $data = $this->validateData($request);
        $campaign->update($data);

        Cache::forget('campaign.active');

        if ($request->wantsJson()) {
            return response()->json([
                'ok' => true,
                'saved_at' => now()->format('H:i:s'),
            ]);
        }

        return redirect()->route('admin.campaigns.edit', $campaign)
            ->with('success', 'Kampanya kaydedildi.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();
        Cache::forget('campaign.active');

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Kampanya silindi.');
    }

    public function toggleActive(Campaign $campaign): JsonResponse
    {
        $campaign->update(['is_active' => ! $campaign->is_active]);
        Cache::forget('campaign.active');

        return response()->json([
            'ok' => true,
            'is_active' => $campaign->is_active,
        ]);
    }

    public function uploadImage(Request $request, Campaign $campaign): JsonResponse
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'kind' => ['nullable', 'in:hero,gallery'],
        ]);

        $kind = $request->input('kind', 'hero');
        $file = $request->file('image');
        $filename = Str::slug($campaign->title ?: 'campaign').'-'.$kind.'-'.time().'-'.Str::random(6).'.'.$file->extension();
        $file->move(public_path('storage/uploads/campaigns'), $filename);

        return response()->json([
            'ok' => true,
            'filename' => $filename,
            'url' => asset('storage/uploads/campaigns/'.$filename),
        ]);
    }

    /** @return array<string, mixed> */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'label_text' => ['nullable', 'string', 'max:50'],

            'old_price' => ['nullable', 'integer', 'min:0'],
            'new_price' => ['nullable', 'integer', 'min:0'],
            'currency' => ['nullable', 'string', 'max:5'],

            'nights' => ['nullable', 'integer', 'min:1', 'max:60'],
            'adults' => ['nullable', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'child_age_limit' => ['nullable', 'integer', 'min:0', 'max:18'],

            'rooms_left' => ['nullable', 'integer', 'min:0'],
            'urgency_text' => ['nullable', 'string', 'max:120'],

            'countdown_enabled' => ['nullable', 'boolean'],
            'valid_until' => ['nullable', 'date'],

            'description' => ['nullable', 'string', 'max:5000'],
            'included_items' => ['nullable', 'array'],
            'included_items.*' => ['nullable', 'string', 'max:200'],

            'hero_image' => ['nullable', 'string', 'max:255'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'string', 'max:255'],

            'is_active' => ['nullable', 'boolean'],
            'show_on_homepage' => ['nullable', 'boolean'],
            'visible_landings' => ['nullable', 'array'],
            'visible_landings.*' => ['nullable', 'string', 'in:'.implode(',', array_keys(self::LANDINGS))],

            'sort_order' => ['nullable', 'integer'],
        ]);

        // Boolean alanlarda checkbox unchecked → field hiç gelmez. Default false.
        foreach (['countdown_enabled', 'is_active', 'show_on_homepage'] as $key) {
            $data[$key] = $request->boolean($key);
        }

        // Boş array'ları normalize et
        $data['included_items'] = array_values(array_filter($data['included_items'] ?? [], fn ($v) => $v !== null && $v !== ''));
        $data['visible_landings'] = array_values($data['visible_landings'] ?? []);
        $data['gallery_images'] = array_values(array_filter($data['gallery_images'] ?? [], fn ($v) => $v !== null && $v !== ''));

        return $data;
    }
}
