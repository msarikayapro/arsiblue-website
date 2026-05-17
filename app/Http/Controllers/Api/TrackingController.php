<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventLog;
use App\Services\MetaCapiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    public function __construct(private MetaCapiService $capi)
    {
    }

    /**
     * POST /api/track-event
     *
     * Frontend'den gelen click/page_view event'lerini:
     *  1. event_logs tablosuna yazar
     *  2. Settings'teki event_mapping'e göre Meta CAPI'ye gönderir
     *  3. (Opsiyonel) GA4/GAds server-side gönderim — Adım 14+ ileride
     */
    public function trackEvent(Request $request): JsonResponse
    {
        // Rate limit (spec § 8.5 — 60/min per IP)
        $key = 'track-event:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 60)) {
            return response()->json(['ok' => false, 'message' => 'rate limit'], 429);
        }
        RateLimiter::hit($key, 60);

        $data = $request->validate([
            'event_name' => ['required', 'string', 'max:50'],
            'event_id' => ['nullable', 'string', 'max:36'],
            'payload' => ['nullable', 'array'],
            'page' => ['nullable', 'string', 'max:255'],
            'referrer' => ['nullable', 'string', 'max:500'],
        ]);

        $eventId = $data['event_id'] ?? (string) Str::uuid();

        $event = EventLog::create([
            'event_name' => $data['event_name'],
            'event_id' => $eventId,
            'session_id' => $request->session()->getId(),
            'user_ip' => $request->ip(),
            'referrer' => $data['referrer'] ?? $request->headers->get('referer'),
            'utm_source' => $request->session()->get('utm_source'),
            'utm_medium' => $request->session()->get('utm_medium'),
            'utm_campaign' => $request->session()->get('utm_campaign'),
            'utm_content' => $request->session()->get('utm_content'),
            'utm_term' => $request->session()->get('utm_term'),
            'landing_page' => $request->session()->get('landing_page'),
            'current_page' => $data['page'] ?? null,
            'payload' => $data['payload'] ?? [],
            'fb_pixel_sent' => true, // Frontend zaten fbq() çağırmış varsayımı
            'created_at' => now(),
        ]);

        // event_mapping'e göre Meta CAPI gönder
        // page_view: Pixel zaten client-side fire ediyor, CAPI'den göndermiyoruz
        // (çift sayım önlemek için — admin'in manuel override'ı için active flag yine de kontrol edilir)
        // Admin formu henüz kaydedilmemişse default mapping'i kullan ki click event'ler
        // CAPI'ye gitsin (admin aksini yazana kadar)
        $defaultMapping = [
            'whatsapp_click' => ['meta' => 'Lead', 'active' => true],
            'phone_click' => ['meta' => 'Contact', 'active' => true],
            'lead_form_submit' => ['meta' => 'Lead', 'active' => true],
            'campaign_click' => ['meta' => 'InitiateCheckout', 'active' => true],
        ];
        $mapping = setting('event_mapping') ?: $defaultMapping;
        $cfg = $mapping[$data['event_name']] ?? null;

        if ($cfg && ($cfg['active'] ?? false) && $data['event_name'] !== 'page_view') {
            $metaEvent = $cfg['meta'] ?? 'CustomEvent';
            $userData = [
                'fbp' => $request->cookie('_fbp'),
                'fbc' => $request->cookie('_fbc'),
            ];
            $this->capi->send($metaEvent, $eventId, $event, $userData, $data['payload'] ?? []);
        }

        return response()->json(['ok' => true, 'event_id' => $eventId]);
    }
}
