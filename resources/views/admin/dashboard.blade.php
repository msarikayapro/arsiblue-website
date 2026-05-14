@extends('layouts.admin')

@section('title', 'Panel')
@section('page-title', 'Panel')

@section('topbar-right')
    @if ($newLeadsCount > 0)
        <span class="rounded-full bg-tertiary-container/30 text-tertiary px-3 py-1 text-label-md">
            {{ $newLeadsCount }} yeni lead
        </span>
    @endif
@endsection

@section('content')
    <div class="max-w-container-max-width mx-auto space-y-8">

        {{-- Welcome strip --}}
        <section class="bg-gradient-to-r from-primary to-primary-container text-on-primary rounded-xl p-6 ambient-shadow-lvl1">
            <h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg">
                Hoş geldiniz, {{ auth()->user()?->name ?? 'Yönetici' }}
            </h2>
            <p class="text-body-md mt-2 opacity-90">
                Bugün {{ now()->translatedFormat('d F Y, l') }} · Son 7 günün özeti aşağıda.
            </p>
        </section>

        {{-- KPI cards --}}
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @php
                $cards = [
                    ['Bugün Ziyaret', $kpis['visits_today'], 'visibility', 'visits'],
                    ['WhatsApp Tıklama', $kpis['whatsapp_today'], 'sms', 'whatsapp'],
                    ['Telefon Tıklama', $kpis['phone_today'], 'call', 'phone'],
                    ['Form Gönderim', $kpis['forms_today'], 'mail', 'forms'],
                ];
            @endphp

            @foreach ($cards as [$label, $value, $icon, $sparkKey])
                <div class="bg-surface-container-lowest rounded-xl p-5 ambient-shadow-lvl1 border border-outline-variant/30">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-label-md text-on-surface-variant">{{ $label }}</span>
                        <span class="material-symbols-outlined text-primary text-[20px]">{{ $icon }}</span>
                    </div>
                    <p class="font-display-lg text-3xl text-on-surface">{{ number_format($value, 0, ',', '.') }}</p>

                    {{-- Mini sparkline --}}
                    @php
                        $values = collect($sparkline)->pluck($sparkKey)->all();
                        $max = max(max($values) ?: 0, 1);
                    @endphp
                    <div class="flex items-end gap-[3px] h-8 mt-3">
                        @foreach ($values as $v)
                            <div class="flex-1 bg-primary-container/40 rounded-sm" style="height: {{ max(5, ($v / $max) * 100) }}%"></div>
                        @endforeach
                    </div>
                    <p class="text-xs text-on-surface-variant mt-2">Son 7 gün</p>
                </div>
            @endforeach
        </section>

        {{-- Active campaign + Tracking health --}}
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Active campaign --}}
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl p-6 ambient-shadow-lvl1 border border-outline-variant/30">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined">local_offer</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md text-on-surface">Aktif Kampanya</h3>
                </div>

                @if ($activeCampaign)
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-[200px]">
                            <p class="text-label-md text-tertiary">{{ $activeCampaign->label_text }}</p>
                            <p class="font-headline-md text-headline-md text-on-surface">{{ $activeCampaign->title }}</p>
                            <p class="text-body-md text-on-surface-variant">{{ $activeCampaign->subtitle }}</p>
                        </div>
                        <div class="text-right">
                            @if ($activeCampaign->old_price)
                                <p class="text-sm text-on-surface-variant line-through">{{ $activeCampaign->oldPriceFormatted() }}</p>
                            @endif
                            <p class="font-display-lg text-2xl text-primary">{{ $activeCampaign->priceFormatted() }}</p>
                        </div>
                    </div>

                    @if ($activeCampaign->rooms_left !== null)
                        <div class="mt-4">
                            <div class="flex justify-between text-label-md text-on-surface-variant mb-1">
                                <span>{{ $activeCampaign->urgency_text ?: 'Kalan oda' }}</span>
                                <span>{{ $activeCampaign->rooms_left }} oda</span>
                            </div>
                            <div class="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                                <div class="bg-tertiary h-full rounded-full" style="width: {{ $activeCampaign->progressPercent() }}%"></div>
                            </div>
                        </div>
                    @endif

                    @if ($activeCampaign->countdown_enabled && $activeCampaign->valid_until)
                        <p class="mt-4 text-label-md text-on-surface-variant">
                            <span class="material-symbols-outlined text-[16px] align-middle">schedule</span>
                            Bitiş: {{ $activeCampaign->valid_until->translatedFormat('d F Y H:i') }}
                        </p>
                    @endif

                    {{-- TODO Adım 6 — Düzenle butonu admin/campaigns/{id}/edit --}}
                @else
                    <p class="text-body-md text-on-surface-variant">Şu anda aktif kampanya yok.</p>
                @endif
            </div>

            {{-- Tracking health --}}
            <div class="bg-surface-container-lowest rounded-xl p-6 ambient-shadow-lvl1 border border-outline-variant/30">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-secondary-container/20 flex items-center justify-center text-secondary">
                        <span class="material-symbols-outlined">monitoring</span>
                    </div>
                    <h3 class="font-headline-md text-headline-md text-on-surface">Tracking Sağlığı</h3>
                </div>
                <ul class="space-y-3">
                    @php
                        $rows = [
                            ['Meta Pixel', $tracking['meta_pixel']],
                            ['Meta CAPI', $tracking['meta_capi']],
                            ['Google Tag Manager', $tracking['gtm']],
                            ['GA4', $tracking['ga4']],
                        ];
                    @endphp
                    @foreach ($rows as [$name, $ok])
                        <li class="flex items-center justify-between text-body-md">
                            <span class="text-on-surface">{{ $name }}</span>
                            <span class="flex items-center gap-2 text-label-md
                                         {{ $ok ? 'text-secondary' : 'text-on-surface-variant' }}">
                                <span class="w-2 h-2 rounded-full {{ $ok ? 'bg-secondary' : 'bg-outline-variant' }}"></span>
                                {{ $ok ? 'Aktif' : 'Pasif' }}
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- Hızlı aksiyonlar --}}
        <section>
            <h3 class="font-headline-md text-headline-md text-on-surface mb-4">Hızlı Aksiyonlar</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @php
                    $quickActions = [
                        ['edit_document', 'Sayfa İçerikleri'],
                        ['local_offer', 'Kampanya Düzenle'],
                        ['photo_library', 'Foto Yükle'],
                        ['monitoring', 'Pixel Ayarları'],
                        ['help', 'SSS Ekle'],
                        ['call', 'İletişim Bilgileri'],
                    ];
                @endphp
                @foreach ($quickActions as [$icon, $label])
                    <button class="bg-surface-container-lowest rounded-xl p-4 ambient-shadow-lvl1 border border-outline-variant/30
                                   hover:-translate-y-1 transition-transform duration-300
                                   flex flex-col items-center gap-2 text-on-surface">
                        <span class="material-symbols-outlined text-primary text-2xl">{{ $icon }}</span>
                        <span class="text-label-md text-center">{{ $label }}</span>
                    </button>
                @endforeach
            </div>
        </section>

        {{-- Son eventler --}}
        <section class="bg-surface-container-lowest rounded-xl p-6 ambient-shadow-lvl1 border border-outline-variant/30">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-headline-md text-headline-md text-on-surface">Son 10 Event</h3>
                <span class="text-xs text-on-surface-variant">30 sn'de bir yenilenir (Adım 11)</span>
            </div>

            @if ($recentEvents->isEmpty())
                <div class="text-center py-12 text-on-surface-variant">
                    <span class="material-symbols-outlined text-4xl block mb-2">inbox</span>
                    Henüz event kaydı yok. Tracking modülü Adım 11 ile aktifleşecek.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-body-md">
                        <thead class="text-label-md text-on-surface-variant border-b border-outline-variant">
                            <tr>
                                <th class="text-left py-2">Zaman</th>
                                <th class="text-left py-2">Event</th>
                                <th class="text-left py-2">Şehir</th>
                                <th class="text-left py-2">Sayfa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentEvents as $event)
                                <tr class="border-b border-outline-variant/30">
                                    <td class="py-2 text-on-surface-variant">{{ $event->created_at->diffForHumans() }}</td>
                                    <td class="py-2"><span class="rounded-full bg-primary-container/20 text-primary px-2 py-1 text-label-md">{{ $event->event_name }}</span></td>
                                    <td class="py-2">{{ $event->city ?? '—' }}</td>
                                    <td class="py-2 truncate max-w-xs">{{ $event->current_page ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>

    </div>
@endsection
