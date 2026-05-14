@extends('layouts.site')

@section('content')

    {{-- Romantic hero --}}
    <section class="relative bg-gradient-to-br from-tertiary via-accent-orange to-accent-yellow py-section-gap-desktop overflow-hidden">
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative max-w-container-max-width mx-auto px-margin-mobile text-center text-white">
            <span class="inline-block bg-white/20 backdrop-blur text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-4">
                Romantik Paket
            </span>
            <h1 class="text-display-lg md:text-6xl font-bold mb-6">{{ $page?->getContent('hero_title') ?? 'Balayınızı Akdeniz\'de Taçlandırın' }}</h1>
            <p class="text-body-lg md:text-xl opacity-90 max-w-2xl mx-auto">{{ $page?->getContent('hero_subtitle') }}</p>
        </div>
    </section>

    {{-- Paket içeriği --}}
    @php $includes = $page?->getContent('package_includes') ?? []; @endphp
    @if (! empty($includes))
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-3xl mx-auto px-margin-mobile">
            <h2 class="text-display-lg md:text-3xl font-bold text-primary text-center mb-8">Pakete Dahil Olanlar</h2>
            <div class="bg-surface-container-lowest rounded-3xl p-6 md:p-8 ambient-shadow-lvl1 border border-outline-variant/30">
                <ul class="space-y-3">
                    @foreach ($includes as $item)
                        <li class="flex items-start gap-3 text-body-lg">
                            <span class="material-symbols-outlined text-tertiary mt-0.5">favorite</span>
                            <span class="text-on-surface">{{ $item }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Romantik bölüm --}}
    <section class="py-section-gap-mobile md:py-section-gap-desktop bg-sand px-margin-mobile">
        <div class="max-w-container-max-width mx-auto grid md:grid-cols-2 gap-gutter items-center">
            <div>
                <h2 class="text-display-lg md:text-3xl font-bold text-primary mb-4">Akdeniz Manzarası ile Özel Anlar</h2>
                <p class="text-body-lg text-on-surface-variant mb-4">
                    Müsaitliğe göre deniz manzaralı oda upgrade, romantik atmosfer, kişiye özel hazırlık.
                    Marina ve sahil restoranları yakın mesafede.
                </p>
                <a href="#bilgi-al" class="inline-flex items-center gap-2 bg-primary text-on-primary px-6 py-3 rounded-full font-semibold hover:opacity-90">
                    Bilgi İste
                    <span class="material-symbols-outlined">arrow_forward</span>
                </a>
            </div>
            <div class="bg-tertiary-container/30 rounded-3xl aspect-square flex items-center justify-center">
                <span class="material-symbols-outlined text-tertiary text-9xl opacity-40">favorite</span>
            </div>
        </div>
    </section>

    {{-- Lead form --}}
    <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-3xl mx-auto px-margin-mobile">
        <x-site.lead-form title="Balayı Paketi İçin Bilgi Al" subtitle="Tarihiniz ve özel isteklerinizle birlikte yazın — kişiye özel teklif hazırlayalım." />
    </section>

@endsection
