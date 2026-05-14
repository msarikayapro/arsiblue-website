@extends('layouts.site')

@section('content')

    {{-- Hero with campaign --}}
    <section class="relative bg-gradient-to-br from-primary via-primary-container to-primary-fixed py-section-gap-mobile md:py-section-gap-desktop overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 rounded-full bg-accent-yellow blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full bg-accent-orange blur-3xl"></div>
        </div>
        <div class="relative z-10 max-w-container-max-width mx-auto px-margin-mobile text-center">
            <span class="inline-block bg-accent-orange text-white text-sm font-bold px-4 py-2 rounded-full uppercase tracking-wider mb-6">
                {{ $page?->getContent('hero_subtitle') ?? '3 gece her şey dahil' }}
            </span>
            <h1 class="text-white text-display-lg md:text-6xl font-bold mb-8">
                {{ $page?->getContent('hero_title') ?? 'Bayrama Özel Aile Tatili' }}
            </h1>
            @if ($campaign)
                <x-site.campaign-card :campaign="$campaign" />
            @endif
        </div>
    </section>

    {{-- Otel mini tanıtım --}}
    <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ([
                ['beach_access', 'Denize Sıfır'],
                ['family_restroom', '%100 Aile Oteli'],
                ['restaurant', 'Her Şey Dahil'],
                ['pool', '3 Havuz + Aqua Park'],
            ] as [$icon, $label])
                <div class="bg-surface-container-lowest rounded-2xl p-4 text-center ambient-shadow-lvl1 border border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-3xl mb-2">{{ $icon }}</span>
                    <p class="text-sm font-semibold text-on-surface">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- CTA bölümü --}}
    <section class="py-section-gap-mobile md:py-section-gap-desktop bg-navy text-white px-margin-mobile">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-display-lg md:text-4xl font-bold mb-4">Hemen yer ayırtın</h2>
            <p class="opacity-90 mb-8">{{ $page?->getContent('cta_text') ?? 'Sınırlı oda kalmıştır — sadece ön ödeme.' }}</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <a href="{{ whatsappLink('Bayrama Özel kampanyası için bilgi almak istiyorum.') }}" target="_blank" data-track="whatsapp"
                   class="bg-whatsapp text-white py-4 rounded-xl font-bold hover:scale-105 transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">sms</span> WhatsApp'tan Sor
                </a>
                <a href="{{ phoneLink(setting('phone_whatsapp')) }}" data-track="phone"
                   class="bg-white/10 backdrop-blur border border-white/20 py-4 rounded-xl font-bold hover:bg-white/20 transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">call</span> Hemen Ara
                </a>
            </div>
            <x-site.lead-form />
        </div>
    </section>

    @if ($faqs->isNotEmpty())
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
            <h2 class="text-display-lg md:text-3xl font-bold text-primary text-center mb-12">Hızlı Cevaplar</h2>
            <x-site.faq-accordion :faqs="$faqs" />
        </section>
    @endif

@endsection
