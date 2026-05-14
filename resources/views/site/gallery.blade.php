@extends('layouts.site')

@section('content')

    <section class="bg-gradient-to-r from-primary to-secondary py-section-gap-mobile px-margin-mobile">
        <div class="max-w-container-max-width mx-auto text-center text-white">
            <h1 class="text-display-lg md:text-5xl font-bold mb-4">Galeri</h1>
            <p class="text-body-lg opacity-90">Otelimizin havuz, plaj, oda ve sosyal alanlarından kareler</p>
        </div>
    </section>

    <section x-data="{ tab: '{{ $items->keys()->first() ?? 'all' }}', lightbox: null }"
             class="py-section-gap-mobile max-w-container-max-width mx-auto px-margin-mobile">

        @if ($items->isNotEmpty())
            {{-- Category tabs --}}
            <div class="flex flex-wrap justify-center gap-2 mb-8">
                @foreach ($items as $cat => $catItems)
                    <button @click="tab = '{{ $cat }}'" type="button"
                            class="px-4 py-2 rounded-full text-label-md font-semibold transition border"
                            :class="tab === '{{ $cat }}' ? 'bg-primary text-on-primary border-primary' : 'bg-surface-container-lowest text-on-surface border-outline-variant hover:border-primary'">
                        {{ ucfirst(str_replace('_', ' ', $cat)) }} ({{ $catItems->count() }})
                    </button>
                @endforeach
            </div>

            {{-- Image grid per category --}}
            @foreach ($items as $cat => $catItems)
                <div x-show="tab === '{{ $cat }}'" x-cloak
                     class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                    @foreach ($catItems as $item)
                        <button @click="lightbox = '{{ asset('storage/uploads/gallery/'.$item->image_path) }}'" type="button"
                                class="aspect-square rounded-2xl overflow-hidden group">
                            <img src="{{ asset('storage/uploads/gallery/'.$item->image_path) }}"
                                 alt="{{ $item->alt_text }}" loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </button>
                    @endforeach
                </div>
            @endforeach

            {{-- Lightbox --}}
            <div x-show="lightbox" x-cloak @click="lightbox = null" @keydown.escape.window="lightbox = null"
                 x-transition.opacity
                 class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4 cursor-zoom-out">
                <img :src="lightbox" alt="" class="max-w-full max-h-full rounded-2xl">
                <button @click="lightbox = null" class="absolute top-4 right-4 text-white p-2 bg-white/10 rounded-full">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
        @else
            <div class="text-center py-16 text-on-surface-variant">
                <span class="material-symbols-outlined text-6xl block mb-4">photo_library</span>
                <p>Henüz galeri görseli eklenmemiş.</p>
            </div>
        @endif
    </section>

@endsection
