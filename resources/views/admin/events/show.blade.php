@extends('layouts.admin')

@section('title', 'Event #'.$event->id)
@section('page-title', 'Event #'.$event->id)

@section('topbar-left')
    <a href="{{ route('admin.events.index') }}" class="p-2 hover:bg-surface-container rounded-full text-on-surface-variant">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h2 class="font-headline-md text-on-surface">Event Detayı</h2>
@endsection

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <x-admin.section-card icon="info" title="Event">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-body-md">
                <div><dt class="text-label-md text-on-surface-variant">Event Name</dt><dd class="text-on-surface font-mono">{{ $event->event_name }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Event ID (UUID)</dt><dd class="text-on-surface font-mono text-xs">{{ $event->event_id }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Zaman</dt><dd class="text-on-surface">{{ $event->created_at?->format('d.m.Y H:i:s') }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Session</dt><dd class="text-on-surface text-xs font-mono">{{ $event->session_id }}</dd></div>
            </dl>
        </x-admin.section-card>

        <x-admin.section-card icon="location_on" title="Konum & Cihaz" variant="secondary">
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 text-body-md">
                <div><dt class="text-label-md text-on-surface-variant">IP</dt><dd class="text-on-surface font-mono text-sm">{{ $event->user_ip ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Şehir</dt><dd class="text-on-surface">{{ $event->city ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Ülke</dt><dd class="text-on-surface">{{ $event->country ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Cihaz</dt><dd class="text-on-surface">{{ $event->device ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Browser</dt><dd class="text-on-surface">{{ $event->browser ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">OS</dt><dd class="text-on-surface">{{ $event->os ?? '—' }}</dd></div>
            </dl>
        </x-admin.section-card>

        <x-admin.section-card icon="campaign" title="UTM & Sayfa" variant="tertiary">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-body-md">
                <div><dt class="text-label-md text-on-surface-variant">Landing</dt><dd class="text-on-surface">{{ $event->landing_page ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Current Page</dt><dd class="text-on-surface">{{ $event->current_page ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">Referrer</dt><dd class="text-on-surface text-xs">{{ $event->referrer ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">UTM Source</dt><dd>{{ $event->utm_source ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">UTM Medium</dt><dd>{{ $event->utm_medium ?? '—' }}</dd></div>
                <div><dt class="text-label-md text-on-surface-variant">UTM Campaign</dt><dd>{{ $event->utm_campaign ?? '—' }}</dd></div>
            </dl>
        </x-admin.section-card>

        <x-admin.section-card icon="send" title="Tracking Gönderim Durumu">
            <ul class="space-y-2 text-body-md">
                @foreach ([['Meta Pixel (client)', $event->fb_pixel_sent], ['Meta CAPI (server)', $event->fb_capi_sent], ['GA4', $event->ga4_sent], ['Google Ads', $event->gads_sent]] as [$label, $sent])
                    <li class="flex items-center justify-between">
                        <span>{{ $label }}</span>
                        <span class="inline-flex items-center gap-1 text-label-md {{ $sent ? 'text-secondary' : 'text-on-surface-variant' }}">
                            <span class="w-2 h-2 rounded-full {{ $sent ? 'bg-secondary' : 'bg-outline-variant' }}"></span>
                            {{ $sent ? 'Gönderildi' : 'Gönderilmedi' }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </x-admin.section-card>

        <x-admin.section-card icon="data_object" title="Payload (JSON)">
            <pre class="bg-surface-container-low rounded-lg p-4 font-mono text-xs overflow-x-auto whitespace-pre-wrap">{{ json_encode($event->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </x-admin.section-card>

    </div>
@endsection
