<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventLog;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackingController extends Controller
{
    public function __construct(private SettingService $settings)
    {
    }

    public function index(): View
    {
        // Default event mapping eğer settings'te yoksa
        $eventMapping = setting('event_mapping') ?? [
            'whatsapp_click' => ['meta' => 'Lead', 'active' => true],
            'phone_click' => ['meta' => 'Contact', 'active' => true],
            'lead_form_submit' => ['meta' => 'Lead', 'active' => true],
            'campaign_click' => ['meta' => 'InitiateCheckout', 'active' => true],
            'page_view' => ['meta' => 'PageView', 'active' => true],
        ];

        return view('admin.tracking.index', [
            'eventMapping' => $eventMapping,
            'metaEvents' => ['Lead', 'Contact', 'InitiateCheckout', 'CompleteRegistration', 'Purchase', 'PageView', 'ViewContent', 'AddToWishlist', 'Search'],
        ]);
    }

    public function updateMeta(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'meta_pixel_id' => ['nullable', 'string', 'regex:/^\d{15,16}$/'],
            'meta_pixel_active' => ['nullable', 'boolean'],
            'meta_capi_token' => ['nullable', 'string', 'max:1000'],
            'meta_capi_test_code' => ['nullable', 'string', 'max:50'],
            'meta_capi_active' => ['nullable', 'boolean'],
            'event_mapping' => ['nullable', 'array'],
        ], [
            'meta_pixel_id.regex' => 'Pixel ID 15 veya 16 hane sayı olmalı.',
        ]);

        $this->settings->set('meta_pixel_id', $data['meta_pixel_id'] ?? '', 'text', 'tracking');
        $this->settings->set('meta_pixel_active', $request->boolean('meta_pixel_active'), 'boolean', 'tracking');

        // CAPI token boş gelmediyse güncelle (boş gelirse mevcut korunur)
        if (! empty($data['meta_capi_token'])) {
            $this->settings->set('meta_capi_token', $data['meta_capi_token'], 'text', 'tracking');
        }
        $this->settings->set('meta_capi_test_code', $data['meta_capi_test_code'] ?? '', 'text', 'tracking');
        $this->settings->set('meta_capi_active', $request->boolean('meta_capi_active'), 'boolean', 'tracking');

        if (isset($data['event_mapping'])) {
            // Sadece bilinen action'ları kabul et
            $sanitized = [];
            foreach ($data['event_mapping'] as $action => $cfg) {
                $sanitized[$action] = [
                    'meta' => (string) ($cfg['meta'] ?? 'CustomEvent'),
                    'active' => (bool) ($cfg['active'] ?? false),
                ];
            }
            $this->settings->set('event_mapping', $sanitized, 'json', 'tracking');
        }

        return redirect()->route('admin.tracking.index', '#meta')->with('success', 'Meta ayarları kaydedildi.');
    }

    public function updateGoogle(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'gtm_container_id' => ['nullable', 'string', 'regex:/^GTM-[A-Z0-9]+$/'],
            'ga4_measurement_id' => ['nullable', 'string', 'regex:/^G-[A-Z0-9]+$/'],
            'google_ads_conversion_id' => ['nullable', 'string', 'regex:/^AW-[A-Z0-9]+$/'],
            'google_ads_conversion_label' => ['nullable', 'string', 'max:120'],
            'google_search_console_verification' => ['nullable', 'string', 'max:255'],
        ], [
            'gtm_container_id.regex' => 'Format: GTM-XXXXXXX',
            'ga4_measurement_id.regex' => 'Format: G-XXXXXXXXXX',
            'google_ads_conversion_id.regex' => 'Format: AW-XXXXXXXXX',
        ]);

        foreach ($data as $key => $value) {
            $this->settings->set($key, $value ?? '', 'text', 'tracking');
        }

        return redirect()->route('admin.tracking.index', '#google')->with('success', 'Google ayarları kaydedildi.');
    }

    public function updateTiktok(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tiktok_pixel_id' => ['nullable', 'string', 'max:60'],
            'tiktok_capi_token' => ['nullable', 'string', 'max:1000'],
            'tiktok_active' => ['nullable', 'boolean'],
        ]);

        $this->settings->set('tiktok_pixel_id', $data['tiktok_pixel_id'] ?? '', 'text', 'tracking');
        if (! empty($data['tiktok_capi_token'])) {
            $this->settings->set('tiktok_capi_token', $data['tiktok_capi_token'], 'text', 'tracking');
        }
        $this->settings->set('tiktok_active', $request->boolean('tiktok_active'), 'boolean', 'tracking');

        return redirect()->route('admin.tracking.index', '#tiktok')->with('success', 'TikTok ayarları kaydedildi.');
    }

    /**
     * Meta CAPI test event endpoint'i.
     * Gerçek implementasyon Adım 11'de MetaCapiService ile gelecek.
     * Şu an placeholder — settings doğruluğunu kontrol eder, sonuç mock döner.
     */
    public function testCapi(): JsonResponse
    {
        $pixelId = setting('meta_pixel_id');
        $token = setting('meta_capi_token');

        if (empty($pixelId) || empty($token)) {
            return response()->json([
                'ok' => false,
                'message' => 'Pixel ID ve CAPI Token önce kaydedilmeli.',
            ], 422);
        }

        // TODO Adım 11: MetaCapiService->send('TestEvent', uuid, [], userData)
        return response()->json([
            'ok' => true,
            'message' => 'Test event gönderimi Adım 11 ile aktifleşecek. Şimdilik settings validation geçti.',
            'pixel_id' => substr($pixelId, 0, 4) . '...' . substr($pixelId, -4),
        ]);
    }

    /**
     * Tracking sağlık durumu — Dashboard ve Tracking sayfası için JSON.
     */
    public function health(): JsonResponse
    {
        return response()->json([
            'meta_pixel' => [
                'configured' => filled(setting('meta_pixel_id')),
                'active' => (bool) setting('meta_pixel_active'),
                'last_event' => EventLog::where('fb_pixel_sent', true)->latest('created_at')->value('created_at'),
            ],
            'meta_capi' => [
                'configured' => filled(setting('meta_capi_token')),
                'active' => (bool) setting('meta_capi_active'),
                'last_event' => EventLog::where('fb_capi_sent', true)->latest('created_at')->value('created_at'),
            ],
            'gtm' => [
                'configured' => filled(setting('gtm_container_id')),
                'active' => filled(setting('gtm_container_id')),
                'last_event' => null,
            ],
            'ga4' => [
                'configured' => filled(setting('ga4_measurement_id')),
                'active' => filled(setting('ga4_measurement_id')),
                'last_event' => EventLog::where('ga4_sent', true)->latest('created_at')->value('created_at'),
            ],
            'google_ads' => [
                'configured' => filled(setting('google_ads_conversion_id')),
                'active' => filled(setting('google_ads_conversion_id')),
                'last_event' => EventLog::where('gads_sent', true)->latest('created_at')->value('created_at'),
            ],
            'tiktok' => [
                'configured' => filled(setting('tiktok_pixel_id')),
                'active' => (bool) setting('tiktok_active'),
                'last_event' => null,
            ],
        ]);
    }
}
