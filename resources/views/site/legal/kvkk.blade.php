@extends('layouts.site')

@section('content')
    <section class="bg-surface-container py-12 px-margin-mobile">
        <div class="max-w-3xl mx-auto">
            <h1 class="text-display-lg md:text-4xl font-bold text-primary mb-2">KVKK Aydınlatma Metni</h1>
            <p class="text-on-surface-variant">Kişisel Verilerin Korunması Kanunu kapsamında bilgilendirme</p>
        </div>
    </section>

    <article class="py-section-gap-mobile max-w-3xl mx-auto px-margin-mobile prose prose-lg">

        @if (setting('agency_name') || setting('agency_kvkk_responsible'))
            <h2>Veri Sorumlusu</h2>
            <p>
                @if (setting('agency_name')) <strong>{{ setting('agency_name') }}</strong>@endif
                @if (setting('agency_tursab_number')) (TÜRSAB Belge No: {{ setting('agency_tursab_number') }})@endif
                — Kişisel verilerinizin işlenmesinden sorumludur.
                @if (setting('agency_kvkk_responsible'))
                    KVKK Veri Sorumlusu: {{ setting('agency_kvkk_responsible') }}.
                @endif
            </p>
        @endif

        <h2>İşlenen Kişisel Veriler</h2>
        <p>
            Web sitemizdeki "Bilgi Al" formu üzerinden tarafınıza ulaşmamız için isim ve telefon numaranızı,
            opsiyonel olarak e-posta adresinizi ve mesajınızı topluyoruz. Ayrıca site ziyaretiniz sırasında
            otomatik olarak IP adresi, cihaz/tarayıcı bilgisi, sayfa görüntüleme istatistikleri kayıt altına
            alınmaktadır.
        </p>

        <h2>İşleme Amaçları</h2>
        <ul>
            <li>Rezervasyon talebinizin değerlendirilmesi ve sizinle iletişime geçilmesi</li>
            <li>Kampanya ve fiyat bilgisi sunulması</li>
            <li>Site performansının ve reklam kanallarının ölçümlenmesi</li>
            <li>Yasal yükümlülüklerin yerine getirilmesi (faturalama, mevzuat)</li>
        </ul>

        <h2>Veri Aktarımı</h2>
        <p>
            Kişisel verileriniz, sadece rezervasyon süreciniz ile sınırlı olarak otel işletmesi ile paylaşılır.
            Yurt dışına veri aktarımı yapılmamaktadır. Reklam ölçüm sağlayıcılarına (Meta, Google) sadece
            anonimleştirilmiş ve hash'lenmiş veriler iletilir.
        </p>

        <h2>Saklama Süresi</h2>
        <p>
            Lead bilgileri 2 yıl, ziyaret kayıtları 1 yıl süre ile saklanır. Bu sürelerin sonunda veriler
            geri dönüşü olmayacak şekilde silinir.
        </p>

        <h2>Haklarınız</h2>
        <p>
            KVKK'nın 11. maddesi kapsamında bilgi alma, düzeltme, silme ve itiraz haklarınızı kullanmak için
            @if (setting('agency_email'))
                <a href="mailto:{{ setting('agency_email') }}">{{ setting('agency_email') }}</a>
            @else
                e-posta adresimiz
            @endif
            üzerinden bizimle iletişime geçebilirsiniz.
            @if (setting('agency_kep'))
                KEP adresimiz: <strong>{{ setting('agency_kep') }}</strong>.
            @endif
        </p>

        <p class="text-sm text-on-surface-variant mt-8 italic">
            Bu metin yasal danışmanın incelemesinden geçmemiş bir taslaktır. Tam metin için acentamıza danışın.
        </p>
    </article>
@endsection
