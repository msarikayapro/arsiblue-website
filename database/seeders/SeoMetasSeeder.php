<?php

namespace Database\Seeders;

use App\Models\SeoMeta;
use Illuminate\Database\Seeder;

class SeoMetasSeeder extends Seeder
{
    public function run(): void
    {
        $metas = [
            ['home', 'Arsi Blue Beach Hotel | Alanya Aile Oteli',
                'Alanya Mahmutlar\'da denize sıfır 4 yıldızlı aile oteli. Her şey dahil, 3 havuz, aqua park. Yetkili acenta üzerinden ön ödemeli rezervasyon.'],
            ['bayrama-ozel', 'Bayrama Özel Tatil Paketi | Arsi Blue Beach',
                'Arsi Blue Beach Hotel\'de bayrama özel her şey dahil 3 gece tatil paketi. Sınırlı sayıda oda, hemen yer ayırtın.'],
            ['balayi-paketi', 'Balayı Paketi | Arsi Blue Beach Alanya',
                'Akdeniz\'de romantik balayı tatili. Müsaitliğe göre deniz manzaralı oda, oda süslemesi, karşılama ikramları.'],
            ['aile-oteli', 'Aile Oteli Alanya | Arsi Blue Beach',
                'Çocuklarınız için güvenli, sizin için huzurlu. Çocuk havuzu, aqua park, günlük animasyon. %100 aile odaklı tatil oteli.'],
            ['odalar', 'Odalarımız | Arsi Blue Beach Hotel',
                'Standart, aile ve müsaitliğe göre deniz manzaralı oda seçenekleri. Konforlu döşeme, klima, Wi-Fi.'],
            ['tesisler', 'Tesisler & Aktiviteler | Arsi Blue Beach',
                '3 havuz, aqua park, kapalı havuz, çakıllı plaj, günlük animasyon ve şovlar.'],
            ['galeri', 'Galeri | Arsi Blue Beach Alanya',
                'Otelimizin havuz, plaj, oda ve sosyal alanlarından fotoğraflar.'],
            ['iletisim', 'İletişim | Arsi Blue Beach Hotel',
                'WhatsApp, telefon ve bilgi formuyla bize ulaşın. Yetkili acenta — şeffaf süreç, hızlı yanıt.'],
            ['kvkk', 'KVKK Aydınlatma Metni | Arsi Blue Beach',
                'Kişisel verilerinizin işlenmesine dair KVKK aydınlatma metni.'],
            ['cerez', 'Çerez Politikası | Arsi Blue Beach',
                'Web sitemizde kullanılan çerezler ve üçüncü taraf araçları hakkında bilgi.'],
            ['hakkimizda', 'Hakkımızda | Arsi Blue Beach',
                'Arsi Blue Beach Hotel ve yetkili acenta hakkında bilgiler.'],
        ];

        foreach ($metas as [$slug, $title, $description]) {
            SeoMeta::updateOrCreate(
                ['page_slug' => $slug],
                ['title' => $title, 'description' => $description, 'robots' => 'index,follow']
            );
        }
    }
}
