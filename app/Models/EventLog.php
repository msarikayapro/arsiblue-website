<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

#[Fillable([
    'event_name', 'event_id',
    'session_id', 'user_ip',
    'country', 'city', 'region',
    'device', 'browser', 'os',
    'referrer',
    'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term',
    'landing_page', 'current_page',
    'payload',
    'fb_pixel_sent', 'fb_capi_sent', 'ga4_sent', 'gads_sent',
])]
class EventLog extends Model
{
    public $timestamps = false;

    protected $dates = ['created_at'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'payload' => 'array',
            'fb_pixel_sent' => 'boolean',
            'fb_capi_sent' => 'boolean',
            'ga4_sent' => 'boolean',
            'gads_sent' => 'boolean',
        ];
    }

    /**
     * Event yarat — request bilgilerinden otomatik doldurur.
     */
    public static function fire(string $eventName, array $payload = [], ?Request $request = null): self
    {
        $request ??= request();

        return static::create([
            'event_name' => $eventName,
            'event_id' => (string) Str::uuid(),
            'session_id' => $request?->session()?->getId(),
            'user_ip' => $request?->ip(),
            'referrer' => $request?->headers?->get('referer'),
            'utm_source' => $request?->query('utm_source') ?? $request?->session()?->get('utm_source'),
            'utm_medium' => $request?->query('utm_medium') ?? $request?->session()?->get('utm_medium'),
            'utm_campaign' => $request?->query('utm_campaign') ?? $request?->session()?->get('utm_campaign'),
            'utm_content' => $request?->query('utm_content') ?? $request?->session()?->get('utm_content'),
            'utm_term' => $request?->query('utm_term') ?? $request?->session()?->get('utm_term'),
            'landing_page' => $request?->session()?->get('landing_page'),
            'current_page' => $request?->path(),
            'payload' => $payload,
            'created_at' => now(),
        ]);
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }

    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeThisWeek(Builder $query): Builder
    {
        return $query->where('created_at', '>=', now()->startOfWeek());
    }

    public function scopeByEvent(Builder $query, string $eventName): Builder
    {
        return $query->where('event_name', $eventName);
    }

    public function scopeByCity(Builder $query, string $city): Builder
    {
        return $query->where('city', $city);
    }

    public function scopeByDevice(Builder $query, string $device): Builder
    {
        return $query->where('device', $device);
    }
}
