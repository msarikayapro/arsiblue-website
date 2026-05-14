@extends('layouts.site')

@section('content')

    {{-- ============ Hero ============ --}}
    <section class="relative h-screen min-h-[700px] flex items-center justify-center -mt-[72px] pt-[72px]">
        <div class="absolute inset-0">
            @php $heroImg = $page?->getContent('hero_image'); @endphp
            @if ($heroImg)
                <img src="{{ asset('storage/uploads/pages/'.$heroImg) }}" alt="" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-primary-container via-primary to-primary-fixed"></div>
            @endif
            <div class="absolute inset-0 bg-black/40"></div>
        </div>
        <div class="relative z-10 text-center px-margin-mobile max-w-4xl">
            <h1 class="text-white text-display-lg md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                {{ $page?->getContent('hero_title') ?? 'Akdeniz\'in Kalbinde, Ailenizle Unutulmaz Bir Tatil' }}
            </h1>
            @if ($page?->getContent('hero_subtitle'))
                <p class="text-white/90 text-body-lg md:text-xl mb-8">{{ $page->getContent('hero_subtitle') }}</p>
            @endif
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                @if ($campaign)
                    <a href="{{ route('landing.bayrama') }}"
                       class="inline-flex items-center justify-center gap-2 bg-accent-orange text-white px-8 py-4 rounded-full font-bold text-lg hover:scale-105 transition shadow-2xl">
                        <span class="material-symbols-outlined">local_offer</span>
                        {{ $campaign->label_text ?: $campaign->title }} — {{ $campaign->priceFormatted() }}
                    </a>
                @endif
                <a href="{{ whatsappLink('Arsi Blue Beach hakkında bilgi almak istiyorum.') }}" target="_blank" data-track="whatsapp"
                   class="inline-flex items-center justify-center gap-2 border-2 border-white text-white bg-white/10 backdrop-blur-sm px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-primary transition">
                    <span class="material-symbols-outlined">sms</span>
                    WhatsApp'tan Sor
                </a>
            </div>
        </div>
    </section>

    {{-- ============ Trust strip ============ --}}
    @php $trustItems = $page?->getContent('trust_strip') ?? []; @endphp
    @if (! empty($trustItems))
        <section class="bg-surface-container py-8 border-b border-outline-variant/30">
            <div class="max-w-container-max-width mx-auto px-margin-mobile flex flex-wrap justify-center gap-6 md:gap-12">
                @foreach ($trustItems as $item)
                    <div class="flex items-center gap-2 text-on-surface-variant">
                        <span class="material-symbols-outlined text-primary text-2xl">verified</span>
                        <span class="font-semibold text-sm md:text-base">{{ $item }}</span>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ Hakkımızda ============ --}}
    @if ($page?->getContent('about_text'))
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile grid md:grid-cols-2 gap-gutter items-center">
            <div>
                <h2 class="text-display-lg md:text-4xl font-bold text-on-background mb-6 leading-tight">
                    {{ $page?->getContent('about_title') ?? 'Akdeniz\'in Mavisinde Huzur Dolu Bir Tatil' }}
                </h2>
                <div class="prose prose-lg text-on-surface-variant">
                    {!! $page->getContent('about_text') !!}
                </div>
            </div>
            <div class="bg-sand rounded-3xl aspect-square flex items-center justify-center">
                <span class="material-symbols-outlined text-primary text-9xl opacity-30">villa</span>
            </div>
        </section>
    @endif

    {{-- ============ Aktif Kampanya ============ --}}
    @if ($campaign)
        <section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface-container px-margin-mobile">
            <div class="max-w-container-max-width mx-auto">
                <h2 class="text-display-lg md:text-4xl font-bold text-center mb-12 text-primary">Bu Sezonun Fırsatı</h2>
                <x-site.campaign-card :campaign="$campaign" />
            </div>
        </section>
    @endif

    {{-- ============ Odalar ============ --}}
    @if ($rooms->isNotEmpty())
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
            <div class="text-center mb-12">
                <h2 class="text-display-lg md:text-4xl font-bold text-primary mb-4">Konforlu Odalarımız</h2>
                <p class="text-body-lg text-on-surface-variant">Standart, aile ve müsaitliğe göre deniz manzaralı seçenekler</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
                @foreach ($rooms as $room)
                    <x-site.room-card :room="$room" />
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ Tesisler & Eğlence ============ --}}
    <section class="py-section-gap-mobile md:py-section-gap-desktop bg-sand px-margin-mobile">
        <div class="max-w-container-max-width mx-auto">
            <div class="text-center mb-12">
                <h2 class="text-display-lg md:text-4xl font-bold text-primary mb-4">Su, Eğlence, Lezzet — Her Şey Dahil</h2>
                <p class="text-body-lg text-on-surface-variant">3 havuz, aqua park, çakıllı plaj ve günlük animasyon</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ([
                    ['icon' => 'water', 'title' => 'Aqua Park', 'desc' => 'Renkli kaydıraklarla eğlence'],
                    ['icon' => 'pool', 'title' => 'Çocuk Havuzu', 'desc' => 'Güvenli, sığ alan'],
                    ['icon' => 'roofing', 'title' => 'Kapalı Havuz', 'desc' => 'Yıl boyu açık'],
                    ['icon' => 'beach_access', 'title' => 'Çakıllı Plaj', 'desc' => 'Yakın mesafede'],
                ] as $facility)
                    <div class="bg-surface-container-lowest rounded-2xl p-6 text-center ambient-shadow-lvl1">
                        <span class="material-symbols-outlined text-primary text-5xl mb-3">{{ $facility['icon'] }}</span>
                        <h3 class="font-semibold text-on-surface mb-1">{{ $facility['title'] }}</h3>
                        <p class="text-sm text-on-surface-variant">{{ $facility['desc'] }}</p>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('facilities') }}" class="inline-flex items-center gap-2 text-primary font-semibold hover:underline">
                    Tüm tesislerimizi gör
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ============ Konum & İletişim quick ============ --}}
    @if (setting('google_maps_embed'))
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
            <div class="text-center mb-8">
                <h2 class="text-display-lg md:text-4xl font-bold text-primary mb-4">Alanya'nın Kalbinde</h2>
            </div>
            <div class="aspect-video rounded-3xl overflow-hidden border border-outline-variant">
                <iframe src="{{ setting('google_maps_embed') }}" width="100%" height="100%"
                        style="border:0" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    @endif

    {{-- ============ Neden Biz? ============ --}}
    @php $whyUs = $page?->getContent('why_us') ?? []; @endphp
    @if (! empty($whyUs))
        <section class="py-section-gap-mobile md:py-section-gap-desktop bg-surface px-margin-mobile">
            <div class="max-w-container-max-width mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-display-lg md:text-4xl font-bold text-primary mb-4">Neden Bizden Rezervasyon Yapmalısınız?</h2>
                    <p class="text-body-lg text-on-surface-variant">Yetkili acenta — şeffaf süreç, hızlı yanıt, en iyi fiyat garantisi</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($whyUs as $item)
                        <div class="bg-surface-container-lowest rounded-2xl p-6 ambient-shadow-lvl1 border border-outline-variant/30">
                            <div class="w-12 h-12 rounded-full bg-primary-container/20 flex items-center justify-center text-primary mb-4">
                                <span class="material-symbols-outlined">{{ $item['icon'] ?? 'check_circle' }}</span>
                            </div>
                            <h3 class="font-headline-md text-on-surface mb-2">{{ $item['title'] ?? '' }}</h3>
                            <p class="text-body-md text-on-surface-variant">{{ $item['text'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ============ SSS ============ --}}
    @if ($faqs->isNotEmpty())
        <section class="py-section-gap-mobile md:py-section-gap-desktop max-w-container-max-width mx-auto px-margin-mobile">
            <h2 class="text-display-lg md:text-4xl font-bold text-primary text-center mb-12">Sık Sorulan Sorular</h2>
            <x-site.faq-accordion :faqs="$faqs" />
        </section>
    @endif

    {{-- ============ Final CTA ============ --}}
    <section class="py-section-gap-mobile md:py-section-gap-desktop bg-navy text-white px-margin-mobile">
        <div class="max-w-container-max-width mx-auto text-center">
            <h2 class="text-display-lg md:text-4xl font-bold mb-4">{{ $page?->getContent('final_cta_title') ?? 'Ailenizle Tatile Hazır mısınız?' }}</h2>
            <p class="text-body-lg opacity-90 mb-12">{{ $page?->getContent('final_cta_subtitle') ?? 'Aşağıdan tercih ettiğiniz iletişim kanalını seçin.' }}</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto mb-12">
                <a href="{{ whatsappLink() }}" target="_blank" data-track="whatsapp"
                   class="bg-whatsapp text-white p-6 rounded-2xl hover:scale-105 transition">
                    <span class="material-symbols-outlined text-4xl block mb-3">sms</span>
                    <p class="font-bold mb-1">WhatsApp Destek</p>
                    <p class="text-sm opacity-90">{{ setting('phone_whatsapp') }}</p>
                </a>
                <a href="{{ phoneLink(setting('phone_landline')) }}" data-track="phone"
                   class="bg-white/10 backdrop-blur text-white p-6 rounded-2xl hover:bg-white/20 transition border border-white/20">
                    <span class="material-symbols-outlined text-4xl block mb-3">call</span>
                    <p class="font-bold mb-1">Sabit Hat</p>
                    <p class="text-sm opacity-90">{{ setting('phone_landline') }}</p>
                </a>
                <a href="{{ phoneLink(setting('phone_gsm')) }}" data-track="phone"
                   class="bg-white/10 backdrop-blur text-white p-6 rounded-2xl hover:bg-white/20 transition border border-white/20">
                    <span class="material-symbols-outlined text-4xl block mb-3">smartphone</span>
                    <p class="font-bold mb-1">GSM</p>
                    <p class="text-sm opacity-90">{{ setting('phone_gsm') }}</p>
                </a>
            </div>

            <div class="max-w-xl mx-auto">
                <x-site.lead-form />
            </div>
        </div>
    </section>

@endsection
