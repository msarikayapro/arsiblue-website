<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\EventLog;
use App\Models\Lead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class LeadController extends Controller
{
    /**
     * POST /bilgi-al
     */
    public function store(Request $request): JsonResponse
    {
        // Honeypot — bot doldurmuşsa sessizce success döndür (spec § 10)
        if ($request->filled('website')) {
            return response()->json(['ok' => true, 'message' => 'Teşekkürler.']);
        }

        // Rate limit (spec § 10 — 5/saat per IP)
        $key = 'lead-form:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'ok' => false,
                'message' => 'Çok fazla deneme. Lütfen sonra tekrar deneyin.',
            ], 429);
        }
        RateLimiter::hit($key, 3600);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'min:10', 'max:30', 'regex:/^[\+\d\s\(\)\-]+$/'],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        $event = EventLog::fire('lead_form_submit', [
            'source' => $request->headers->get('referer'),
        ], $request);

        $lead = Lead::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'message' => $data['message'] ?? null,
            'landing_page' => $request->headers->get('referer'),
            'utm_source' => $event->utm_source,
            'utm_medium' => $event->utm_medium,
            'utm_campaign' => $event->utm_campaign,
            'user_ip' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 500),
            'status' => 'yeni',
            'event_log_id' => $event->id,
        ]);

        // Admin'e mail bildirimi (queue'ya at)
        try {
            $adminEmail = env('ADMIN_NOTIFICATION_EMAIL');
            if ($adminEmail) {
                Mail::send('emails.new-lead', ['lead' => $lead], function ($m) use ($adminEmail, $lead) {
                    $m->to($adminEmail)->subject('Yeni Lead: '.$lead->name);
                });
            }
        } catch (\Throwable $e) {
            // Mail başarısız olsa bile lead'i kaybetme — log'la geç
            logger()->warning('Lead notification mail failed', ['e' => $e->getMessage()]);
        }

        // Server-side CAPI (Adım 11'de gerçek gönderim — şimdilik direct fire çalışır)
        // app(\App\Services\MetaCapiService::class)->sendLead($lead, $event);

        return response()->json([
            'ok' => true,
            'message' => 'Bilgi alındı. Sizi en kısa sürede arayacağız.',
            'lead_id' => $lead->id,
        ]);
    }
}
