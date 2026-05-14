@extends('layouts.site')

@section('content')

    {{-- Hero --}}
    <section class="relative bg-gradient-to-br from-secondary via-secondary-container to-primary-fixed py-section-gap-desktop overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-container-max-width mx-auto px-margin-mobile text-center text-white">
            <span class="inline-block bg-accent-yellow text-navy text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">
                %100 Aile Oteli
            </span>
            <h1 class="text-display-lg md:text-6xl font-bold mb-6">{{ $page?->getContent('hero_title') ?? 'Çocuklarınız İçin Güvenli, Sizin İçin Huzurlu' }}</h1>
            <p class="text-body-lg md:text-xl opacity-95">{{ $page?->getContent('hero_subtitle') }}</p>
        </div>
    </section>

    {{-- Aile özellikleri --}}
    @php $features = $page?->getContent('family_features') ?? []; @endphp
    @if (! empty($features))
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
            <h2 class="text-display-lg md:text-3xl font-bold text-primary text-center mb-12">Çocuk Dostu Özellikler</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach ($features as $f)
                    <div class="bg-surface-container-lowest rounded-2xl p-6 text-center ambient-shadow-lvl1 border border-outline-variant/30 hover:-translate-y-1 transition">
                        <span class="material-symbols-outlined text-primary text-5xl mb-3">{{ $f['icon'] ?? 'family_restroom' }}</span>
                        <h3 class="font-headline-md text-on-surface mb-2">{{ $f['title'] ?? '' }}</h3>
                        <p class="text-body-md text-on-surface-variant">{{ $f['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Güvenlik vurgusu (pozitif framing) --}}
    @if ($page?->getContent('safety_text'))
        <section class="bg-sand py-section-gap-mobile md:py-section-gap-desktop px-margin-mobile">
            <div class="max-w-3xl mx-auto text-center">
                <span class="material-symbols-outlined text-secondary text-6xl mb-4">verified_user</span>
                <h2 class="text-display-lg md:text-3xl font-bold text-primary mb-4">Aileler İçin Güvenli Tatil</h2>
                <div class="prose prose-lg text-on-surface-variant mx-auto">
                    {!! $page->getContent('safety_text') !!}
                </div>
            </div>
        </section>
    @endif

    {{-- CTA --}}
    <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-3xl mx-auto px-margin-mobile">
        <x-site.lead-form title="Aile Tatili İçin Bilgi Al" subtitle="Çocuk yaşı ve tarihinizi bildirin — size en uygun oda+paket önerelim." />
    </section>

    @if ($faqs->isNotEmpty())
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
            <h2 class="text-display-lg md:text-3xl font-bold text-primary text-center mb-12">Aileler Bunu Soruyor</h2>
            <x-site.faq-accordion :faqs="$faqs" />
        </section>
    @endif

@endsection
