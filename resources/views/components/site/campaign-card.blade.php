@props(['campaign'])

<div class="bg-surface-container-lowest rounded-3xl overflow-hidden ambient-shadow-lvl2 border border-outline-variant/30 max-w-2xl mx-auto">

    @if ($campaign->hero_image)
        <img src="{{ asset('storage/uploads/campaigns/'.$campaign->hero_image) }}" alt="{{ $campaign->title }}"
             class="w-full h-56 md:h-72 object-cover" loading="lazy">
    @else
        <div class="h-32 bg-gradient-to-r from-primary to-primary-container"></div>
    @endif

    <div class="p-6 md:p-8">

        {{-- Label + countdown --}}
        <div class="flex items-center justify-between mb-3">
            @if ($campaign->label_text)
                <span class="inline-block bg-tertiary text-on-tertiary text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">
                    {{ $campaign->label_text }}
                </span>
            @endif
            @if ($campaign->urgency_text)
                <span class="text-tertiary text-label-md font-semibold">
                    <span class="material-symbols-outlined text-[16px] align-middle">local_fire_department</span>
                    {{ $campaign->urgency_text }}
                </span>
            @endif
        </div>

        <h3 class="font-display-lg text-2xl md:text-3xl font-bold text-on-surface mb-2">{{ $campaign->title }}</h3>
        @if ($campaign->subtitle)
            <p class="text-body-lg text-on-surface-variant mb-6">{{ $campaign->subtitle }}</p>
        @endif

        {{-- Pricing --}}
        <div class="flex items-baseline gap-3 mb-6">
            @if ($campaign->old_price)
                <span class="text-on-surface-variant text-lg line-through">{{ $campaign->oldPriceFormatted() }}</span>
            @endif
            <span class="font-display-lg text-4xl md:text-5xl text-primary font-bold">{{ $campaign->priceFormatted() }}</span>
            @if ($campaign->nights)
                <span class="text-on-surface-variant text-sm">/ {{ $campaign->nights }} gece</span>
            @endif
        </div>

        {{-- Countdown --}}
        @if ($campaign->countdown_enabled && $campaign->valid_until && $campaign->valid_until->isFuture())
            <div x-data="{
                end: new Date(@js($campaign->valid_until->toIso8601String())).getTime(),
                d: 0, h: 0, m: 0, s: 0,
                tick() {
                    const diff = this.end - Date.now();
                    if (diff <= 0) { this.d = this.h = this.m = this.s = 0; return; }
                    this.d = Math.floor(diff / 86400000);
                    this.h = Math.floor((diff % 86400000) / 3600000);
                    this.m = Math.floor((diff % 3600000) / 60000);
                    this.s = Math.floor((diff % 60000) / 1000);
                }
            }" x-init="tick(); setInterval(() => tick(), 1000)"
               class="grid grid-cols-4 gap-2 mb-6">
                @foreach ([['d', 'Gün'], ['h', 'Saat'], ['m', 'Dakika'], ['s', 'Saniye']] as [$key, $label])
                    <div class="bg-primary-container/20 rounded-xl p-3 text-center">
                        <div class="text-2xl md:text-3xl font-bold text-primary" x-text="String({{ $key }}).padStart(2, '0')">00</div>
                        <div class="text-xs text-on-surface-variant uppercase tracking-wider">{{ $label }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Included items --}}
        @if (! empty($campaign->included_items))
            <ul class="space-y-2 mb-6">
                @foreach ($campaign->included_items as $item)
                    <li class="flex items-start gap-3 text-body-md text-on-surface">
                        <span class="material-symbols-outlined text-secondary text-[20px] shrink-0 mt-0.5">check_circle</span>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        @endif

        {{-- Rooms left progress --}}
        @if ($campaign->rooms_left !== null && $campaign->rooms_left > 0)
            <div class="mb-6">
                <div class="flex justify-between text-xs text-on-surface-variant mb-1">
                    <span>Doluluk</span>
                    <span>{{ $campaign->rooms_left }} oda kaldı</span>
                </div>
                <div class="w-full bg-surface-container rounded-full h-2 overflow-hidden">
                    <div class="bg-tertiary h-full rounded-full" style="width: {{ $campaign->progressPercent() }}%"></div>
                </div>
            </div>
        @endif

        {{-- CTAs --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ whatsappLink('Merhaba, '.$campaign->title.' kampanyası hakkında bilgi almak istiyorum.') }}" target="_blank"
               data-track="whatsapp" data-track-payload='{"campaign":"{{ $campaign->title }}"}'
               class="flex-1 bg-whatsapp text-white text-center py-4 rounded-xl font-bold hover:scale-105 transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">sms</span>
                WhatsApp ile Sor
            </a>
            <a href="#bilgi-al" data-track="campaign_click" data-track-payload='{"campaign":"{{ $campaign->title }}"}'
               class="flex-1 bg-primary text-on-primary text-center py-4 rounded-xl font-bold hover:opacity-90 transition flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">edit_note</span>
                Bilgi İste
            </a>
        </div>
    </div>
</div>
