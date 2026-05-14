@props(['room'])

<div class="bg-surface-container-lowest rounded-2xl overflow-hidden ambient-shadow-lvl1 border border-outline-variant/30 hover:-translate-y-1 transition-transform duration-300">
    @if ($room->main_image)
        <img src="{{ asset('storage/uploads/rooms/'.$room->main_image) }}" alt="{{ $room->name }}"
             class="w-full h-48 object-cover" loading="lazy">
    @else
        <div class="w-full h-48 bg-surface-container flex items-center justify-center text-on-surface-variant">
            <span class="material-symbols-outlined text-5xl">bed</span>
        </div>
    @endif

    <div class="p-6">
        <h3 class="font-headline-md text-headline-md text-primary mb-2">{{ $room->name }}</h3>
        @if ($room->short_description)
            <p class="text-body-md text-on-surface-variant mb-4">{{ $room->short_description }}</p>
        @endif

        @if (! empty($room->features))
            @php
                $featureLabels = [
                    'bed_double' => ['icon' => 'bed', 'label' => 'Çift Yatak'],
                    'bed_single' => ['icon' => 'single_bed', 'label' => 'Tek Yatak'],
                    'ac' => ['icon' => 'ac_unit', 'label' => 'Klima'],
                    'tv' => ['icon' => 'tv', 'label' => 'TV'],
                    'wifi' => ['icon' => 'wifi', 'label' => 'Wi-Fi'],
                    'fridge' => ['icon' => 'kitchen', 'label' => 'Buzdolabı'],
                    'safe' => ['icon' => 'lock', 'label' => 'Kasa'],
                    'balcony' => ['icon' => 'balcony', 'label' => 'Balkon'],
                    'sea_view' => ['icon' => 'water', 'label' => 'Deniz Manzarası'],
                    'family_friendly' => ['icon' => 'family_restroom', 'label' => 'Aile Dostu'],
                ];
            @endphp
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach ($room->features as $feat)
                    @if (isset($featureLabels[$feat]))
                        <span class="inline-flex items-center gap-1 bg-primary-container/20 text-primary px-2 py-1 rounded-full text-xs">
                            <span class="material-symbols-outlined text-[14px]">{{ $featureLabels[$feat]['icon'] }}</span>
                            {{ $featureLabels[$feat]['label'] }}
                        </span>
                    @endif
                @endforeach
            </div>
        @endif

        <p class="text-xs text-on-surface-variant italic mb-4">{{ $room->availability_note }}</p>

        <a href="#bilgi-al" class="inline-flex items-center gap-2 text-primary font-semibold hover:underline">
            Bu oda için bilgi al
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </a>
    </div>
</div>
