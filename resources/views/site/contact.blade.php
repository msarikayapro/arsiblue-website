@extends('layouts.site')

@section('content')

    <section class="bg-gradient-to-r from-primary to-primary-container py-section-gap-mobile px-margin-mobile">
        <div class="max-w-container-max-width mx-auto text-center text-white">
            <h1 class="text-display-lg md:text-5xl font-bold mb-4">Bize Ulaşın</h1>
            <p class="text-body-lg opacity-90">Yetkili acenta — şeffaf süreç, hızlı yanıt, ön ödemeli rezervasyon</p>
        </div>
    </section>

    {{-- 3 telefon kartı --}}
    <section class="py-section-gap-mobile max-w-container-max-width mx-auto px-margin-mobile">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @if (setting('phone_landline'))
                <a href="{{ phoneLink(setting('phone_landline')) }}" data-track="phone"
                   class="bg-surface-container-lowest rounded-3xl p-6 text-center ambient-shadow-lvl1 border border-outline-variant/30 hover:-translate-y-1 transition">
                    <span class="material-symbols-outlined text-primary text-5xl mb-3">call</span>
                    <p class="font-bold text-on-surface mb-1">Sabit Hat</p>
                    <p class="text-body-lg text-primary font-mono">{{ setting('phone_landline') }}</p>
                </a>
            @endif
            @if (setting('phone_whatsapp'))
                <a href="{{ whatsappLink() }}" target="_blank" data-track="whatsapp"
                   class="bg-whatsapp text-white rounded-3xl p-6 text-center ambient-shadow-lvl1 hover:scale-105 transition">
                    <span class="material-symbols-outlined text-5xl mb-3" style="font-variation-settings: 'FILL' 1;">sms</span>
                    <p class="font-bold mb-1">WhatsApp</p>
                    <p class="text-body-lg font-mono">{{ setting('phone_whatsapp') }}</p>
                </a>
            @endif
            @if (setting('phone_gsm'))
                <a href="{{ phoneLink(setting('phone_gsm')) }}" data-track="phone"
                   class="bg-surface-container-lowest rounded-3xl p-6 text-center ambient-shadow-lvl1 border border-outline-variant/30 hover:-translate-y-1 transition">
                    <span class="material-symbols-outlined text-primary text-5xl mb-3">smartphone</span>
                    <p class="font-bold text-on-surface mb-1">GSM</p>
                    <p class="text-body-lg text-primary font-mono">{{ setting('phone_gsm') }}</p>
                </a>
            @endif
        </div>
    </section>

    <section class="py-section-gap-mobile max-w-container-max-width mx-auto px-margin-mobile grid md:grid-cols-2 gap-gutter">

        {{-- Adres + Map --}}
        <div class="space-y-4">
            <h2 class="text-headline-lg font-bold text-primary">Adres</h2>
            @if (setting('address'))
                <p class="text-body-lg text-on-surface">{{ setting('address') }}</p>
            @endif
            @if (setting('working_hours'))
                <p class="text-body-md text-on-surface-variant">
                    <span class="material-symbols-outlined text-[18px] align-middle text-primary">schedule</span>
                    {{ setting('working_hours') }}
                </p>
            @endif
            @if (setting('google_maps_embed'))
                <div class="aspect-square rounded-3xl overflow-hidden border border-outline-variant">
                    <iframe src="{{ setting('google_maps_embed') }}" width="100%" height="100%"
                            style="border:0" allowfullscreen loading="lazy"></iframe>
                </div>
            @endif
            @if (setting('agency_tursab_pdf'))
                <a href="{{ asset('storage/agency/'.setting('agency_tursab_pdf')) }}" target="_blank"
                   class="inline-flex items-center gap-2 text-primary hover:underline">
                    <span class="material-symbols-outlined">picture_as_pdf</span>
                    TÜRSAB belgemizi görüntüle
                </a>
            @endif
        </div>

        {{-- Form --}}
        <div>
            <x-site.lead-form />
        </div>
    </section>

    @if ($faqs->isNotEmpty())
        <section class="py-section-gap-mobile max-w-container-max-width mx-auto px-margin-mobile">
            <h2 class="text-display-lg md:text-3xl font-bold text-primary text-center mb-12">Sık Sorulanlar</h2>
            <x-site.faq-accordion :faqs="$faqs" />
        </section>
    @endif

@endsection
