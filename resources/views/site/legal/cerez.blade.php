@extends('layouts.site')

@section('content')
    <section class="bg-surface-container py-12 px-margin-mobile">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-display-lg md:text-4xl font-bold text-primary mb-2">Çerez Politikası</h1>
            <p class="text-on-surface-variant">Web sitemizde kullanılan çerezler ve üçüncü taraf araçları</p>
        </div>
    </section>

    <article class="py-section-gap-mobile max-w-3xl mx-auto px-margin-mobile prose prose-lg">
        <h2>Çerez Nedir?</h2>
        <p>Çerezler (cookies), web sitesini ziyaret ettiğinizde tarayıcınıza kaydedilen küçük metin dosyalarıdır.</p>

        <h2>Kullandığımız Çerezler</h2>

        <h3>Zorunlu Çerezler</h3>
        <ul>
            <li><strong>Laravel Session:</strong> Form gönderimi sırasında oturum yönetimi için kullanılır.</li>
            <li><strong>CSRF Token:</strong> Güvenlik amaçlı, form gönderimlerinin doğruluğunu kontrol eder.</li>
        </ul>

        <h3>Performans / Analitik Çerezleri</h3>
        <ul>
            @if (setting('ga4_measurement_id') || setting('gtm_container_id'))
                <li><strong>Google Analytics (GA4):</strong> Sayfa ziyaretlerini, kullanıcı davranışını ölçümler.</li>
            @endif
            @if (setting('gtm_container_id'))
                <li><strong>Google Tag Manager:</strong> Diğer ölçüm araçlarını yönetir.</li>
            @endif
        </ul>

        <h3>Reklam / Pazarlama Çerezleri</h3>
        <ul>
            @if (setting('meta_pixel_id') && setting('meta_pixel_active'))
                <li><strong>Meta Pixel (Facebook/Instagram):</strong> Reklamların etkinliğini ölçer ve hedeflemeyi iyileştirir.</li>
            @endif
            @if (setting('google_ads_conversion_id'))
                <li><strong>Google Ads Conversion:</strong> Google reklamlarından gelen dönüşümleri takip eder.</li>
            @endif
            @if (setting('tiktok_pixel_id') && setting('tiktok_active'))
                <li><strong>TikTok Pixel:</strong> TikTok kampanyalarının etkinliğini ölçer.</li>
            @endif
        </ul>

        <h2>Çerezleri Yönetme</h2>
        <p>
            Tarayıcınızın ayarlarından çerezleri silebilir veya engelleyebilirsiniz. Bu durumda site
            performansı ve bazı özellikler etkilenebilir.
        </p>
    </article>
@endsection
