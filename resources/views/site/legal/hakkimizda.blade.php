@extends('layouts.site')

@section('content')
    <section class="bg-gradient-to-r from-primary to-primary-container py-section-gap-mobile px-margin-mobile">
        <div class="max-w-3xl mx-auto text-center text-white">
            <h1 class="text-display-lg md:text-5xl font-bold mb-4">Hakkımızda</h1>
            <p class="text-body-lg opacity-90">Arsi Blue Beach Hotel'in yetkili acentası</p>
        </div>
    </section>

    <article class="py-section-gap-mobile max-w-3xl mx-auto px-margin-mobile prose prose-lg">

        <p>
            Bu site, Arsi Blue Beach Hotel'in tanıtımı ve rezervasyon süreçlerinin yönetimi için
            <strong>yetkili acentası</strong> tarafından işletilmektedir. "Resmi otel sitesi" değiliz —
            Türkiye Seyahat Acentaları Birliği (TÜRSAB) üyesi, lisanslı bir seyahat acentasıyız.
        </p>

        <h2>Şeffaf Hizmet Yaklaşımımız</h2>
        <p>
            Online rezervasyon kabul etmiyoruz. Tüm rezervasyon süreçleri telefon, WhatsApp veya
            "Bilgi Al" formu üzerinden başlar; sadece <strong>ön ödemeli</strong> olarak alınır.
            Saklı ücret yok, sürpriz yok.
        </p>

        <h2>Kimiz?</h2>
        <ul>
            @if (setting('agency_name'))
                <li><strong>Ticari Adımız:</strong> {{ setting('agency_name') }}</li>
            @endif
            @if (setting('agency_tursab_number'))
                <li><strong>TÜRSAB Belge No:</strong> {{ setting('agency_tursab_number') }}</li>
            @endif
            @if (setting('agency_tax_number'))
                <li><strong>Vergi No:</strong> {{ setting('agency_tax_number') }}
                    @if (setting('agency_tax_office')) ({{ setting('agency_tax_office') }})@endif
                </li>
            @endif
            @if (setting('agency_mersis'))
                <li><strong>MERSİS:</strong> {{ setting('agency_mersis') }}</li>
            @endif
        </ul>

        @if (setting('agency_tursab_pdf'))
            <p>
                <a href="{{ asset('storage/agency/'.setting('agency_tursab_pdf')) }}" target="_blank"
                   class="inline-flex items-center gap-2">
                    <span class="material-symbols-outlined">picture_as_pdf</span>
                    TÜRSAB belgemizi indirin
                </a>
            </p>
        @endif

        <h2>Bize Ulaşın</h2>
        <p>
            @if (setting('phone_landline'))Telefon: <strong>{{ setting('phone_landline') }}</strong> ·@endif
            @if (setting('phone_whatsapp')) WhatsApp: <strong>{{ setting('phone_whatsapp') }}</strong> ·@endif
            @if (setting('email')) E-posta: <a href="mailto:{{ setting('email') }}">{{ setting('email') }}</a>@endif
        </p>
    </article>
@endsection
