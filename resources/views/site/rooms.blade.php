@extends('layouts.site')

@section('content')

    <section class="bg-gradient-to-r from-primary to-primary-container py-section-gap-mobile px-margin-mobile">
        <div class="max-w-container-max-width mx-auto text-center text-white">
            <h1 class="text-display-lg md:text-5xl font-bold mb-4">{{ $page?->getContent('hero_title') ?? 'Yenilenmiş, Konforlu Odalarımız' }}</h1>
            <p class="text-body-lg opacity-90">{{ $page?->getContent('hero_subtitle') }}</p>
        </div>
    </section>

    @if ($page?->getContent('intro_text'))
        <section class="py-section-gap-mobile max-w-3xl mx-auto px-margin-mobile">
            <div class="prose prose-lg text-on-surface-variant text-center mx-auto">
                {!! $page->getContent('intro_text') !!}
            </div>
        </section>
    @endif

    <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
            @foreach ($rooms as $room)
                <x-site.room-card :room="$room" />
            @endforeach
        </div>
    </section>

    <section class="py-section-gap-mobile md:py-section-gap-desktop bg-sand px-margin-mobile">
        <div class="max-w-3xl mx-auto">
            <x-site.lead-form title="Oda Müsaitliği Sor" subtitle="Tarihinizi ve oda tipinizi belirtin — müsaitlik kontrolü yapalım." />
        </div>
    </section>

@endsection
