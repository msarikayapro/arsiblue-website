@php
    $nav = [
        ['route' => 'home', 'label' => 'Otelimiz'],
        ['route' => 'rooms', 'label' => 'Odalar'],
        ['route' => 'facilities', 'label' => 'Tesisler'],
        ['route' => 'gallery', 'label' => 'Galeri'],
        ['route' => 'contact', 'label' => 'İletişim'],
    ];
@endphp

<nav x-data="{ mobileOpen: false, scrolled: false }"
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)"
     class="fixed top-0 w-full z-50 backdrop-blur-md border-b border-outline-variant/20 transition-all"
     :class="scrolled ? 'bg-surface/95 shadow-sm' : 'bg-surface/80'">

    <div class="flex justify-between items-center h-[72px] px-margin-mobile md:px-gutter max-w-container-max-width mx-auto">

        {{-- Logo / brand --}}
        <a href="{{ route('home') }}" class="flex items-center gap-2 text-headline-md font-bold tracking-tight text-primary">
            @if (setting('site_logo'))
                <img src="{{ asset('storage/uploads/'.setting('site_logo')) }}" alt="" class="h-10 w-auto">
            @else
                {{ setting('site_name', 'Arsi Blue Beach') }}
            @endif
        </a>

        {{-- Desktop nav --}}
        <div class="hidden md:flex items-center gap-1">
            @foreach ($nav as $item)
                @php
                    $href = \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : '/';
                    $active = request()->routeIs($item['route']);
                @endphp
                <a href="{{ $href }}"
                   class="px-4 py-2 text-label-md font-semibold transition-colors
                          {{ $active ? 'text-primary border-b-2 border-primary' : 'text-on-surface-variant hover:text-primary' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Right CTAs --}}
        <div class="flex items-center gap-2 md:gap-3">
            <a href="{{ phoneLink(setting('phone_landline')) }}" data-track="phone"
               class="hidden sm:inline-flex items-center gap-2 text-primary px-4 py-2 rounded-full text-label-md font-semibold hover:bg-primary-container/20 transition">
                <span class="material-symbols-outlined text-[18px]">call</span>
                <span class="hidden lg:inline">Hemen Ara</span>
            </a>
            <a href="{{ whatsappLink('Arsi Blue Beach hakkında bilgi almak istiyorum.') }}" target="_blank" data-track="whatsapp"
               class="bg-primary text-on-primary px-4 md:px-6 py-2.5 rounded-full text-label-md font-semibold hover:opacity-90 transition flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">sms</span>
                <span class="hidden md:inline">WhatsApp</span>
            </a>

            {{-- Mobile hamburger --}}
            <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 text-on-surface" aria-label="Menü">
                <span class="material-symbols-outlined" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
            </button>
        </div>
    </div>

    {{-- Mobile drawer --}}
    <div x-show="mobileOpen" x-cloak x-transition.opacity
         class="md:hidden bg-surface-container-lowest border-t border-outline-variant">
        <ul class="px-margin-mobile py-4 space-y-1">
            @foreach ($nav as $item)
                @php
                    $href = \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : '/';
                    $active = request()->routeIs($item['route']);
                @endphp
                <li>
                    <a href="{{ $href }}"
                       class="block px-4 py-3 rounded-lg text-label-md
                              {{ $active ? 'bg-primary-container/20 text-primary font-semibold' : 'text-on-surface hover:bg-surface-container' }}">
                        {{ $item['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>

{{-- Header offset spacer --}}
<div class="h-[72px]"></div>
