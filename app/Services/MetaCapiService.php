<?php

namespace App\Services;

use App\Models\EventLog;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MetaCapiService
{
    private const ENDPOINT = 'https://graph.facebook.com/v18.0/%s/events';

    /**
     * Verilen event'i Meta CAPI'ye gönderir.
     *
     * @param array{email?:string,phone?:string,fbp?:string,fbc?:string} $userData
     * @param array<string, mixed>                                       $customData
     */
    public function send(string $eventName, string $eventId, EventLog $event, array $userData = [], array $customData = []): bool
    {
        $pixelId = setting('meta_pixel_id');
        $token = setting('meta_capi_token');

        if (empty($pixelId) || empty($token) || ! setting('meta_capi_active')) {
            return false;
        }

        $payload = [
            'data' => [[
                'event_name' => $eventName,
                'event_time' => $event->created_at?->timestamp ?? time(),
                'event_id' => $eventId,
                'action_source' => 'website',
                'event_source_url' => $event->referrer ?: url($event->current_page ?? '/'),
                'user_data' => array_filter([
                    'client_ip_address' => $event->user_ip,
                    'client_user_agent' => substr(request()->userAgent() ?? '', 0, 500),
                    'fbp' => $userData['fbp'] ?? null,
                    'fbc' => $userData['fbc'] ?? null,
                    'em' => isset($userData['email']) ? hash('sha256', strtolower(trim($userData['email']))) : null,
                    'ph' => isset($userData['phone']) ? hash('sha256', preg_replace('/[^0-9]/', '', $userData['phone'])) : null,
                ]),
                'custom_data' => $customData,
            ]],
        ];

        // Test event code (opsiyonel)
        if ($testCode = setting('meta_capi_test_code')) {
            $payload['test_event_code'] = $testCode;
        }

        try {
            $response = Http::timeout(5)
                ->withToken($token)
                ->post(sprintf(self::ENDPOINT, $pixelId), $payload);

            $ok = $response->successful();
            $event->update(['fb_capi_sent' => $ok]);

            if (! $ok) {
                Log::warning('Meta CAPI failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'event_id' => $eventId,
                ]);
            }

            return $ok;
        } catch (ConnectionException $e) {
            Log::error('Meta CAPI connection error', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
