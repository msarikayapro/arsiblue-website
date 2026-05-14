@extends('layouts.site')

@section('content')

    <section class="bg-gradient-to-r from-secondary to-primary py-section-gap-mobile px-margin-mobile">
        <div class="max-w-container-max-width mx-auto text-center text-white">
            <h1 class="text-display-lg md:text-5xl font-bold mb-4">{{ $page?->getContent('hero_title') ?? 'Tesisler & Aktiviteler' }}</h1>
            <p class="text-body-lg opacity-90">{{ $page?->getContent('hero_subtitle') }}</p>
        </div>
    </section>

    @php $items = $page?->getContent('facilities') ?? []; @endphp
    @if (! empty($items))
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-gutter">
                @foreach ($items as $item)
                    <div class="bg-surface-container-lowest rounded-3xl p-8 ambient-shadow-lvl1 border border-outline-variant/30 hover:-translate-y-1 transition">
                        <div class="w-16 h-16 rounded-full bg-primary-container/20 flex items-center justify-center text-primary mb-6">
                            <span class="material-symbols-outlined text-3xl">{{ $item['icon'] ?? 'spa' }}</span>
                        </div>
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-3">{{ $item['title'] ?? '' }}</h3>
                        <p class="text-body-md text-on-surface-variant">{{ $item['text'] ?? '' }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <section class="py-section-gap-mobile md:py-section-gap-desktop bg-sand px-margin-mobile">
        <div class="max-w-3xl mx-auto">
            <x-site.lead-form title="Daha Fazla Bilgi" />
        </div>
    </section>

@endsection
