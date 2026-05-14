<?php

namespace Database\Seeders;

use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqsSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            ['Otele giriş ve çıkış saatleri nedir?', 'Standart giriş 14:00, çıkış 12:00\'dir. Müsaitliğe göre erken giriş veya geç çıkış için bizimle iletişime geçin.', 'genel'],
            ['Her şey dahil konsepti nelerden oluşuyor?', 'Açık büfe kahvaltı, öğle ve akşam yemekleri, snack alanları, sınırsız alkollü ve alkolsüz içecekler (09:00–22:00 arası), 3 havuz, aqua park ve günlük animasyon dahildir.', 'genel'],
            ['Çocuklar için indirim var mı?', 'Aile odalarında 12 yaş altı çocuklar belirli yaş aralıklarında indirimlidir. Detaylar için bizimle görüşmenizi rica ederiz.', 'aile'],
            ['Plaj otele uzak mı?', 'Plaj otele yakın mesafededir. Otel şezlongları ücretsiz olarak misafirlerimize sunulur.', 'genel'],
            ['Deniz manzaralı oda alabilir miyim?', 'Müsaitliğe göre deniz manzaralı oda talebinizi alıyoruz. Rezervasyon sırasında belirtmeniz halinde öncelikli olarak değerlendirilir.', 'oda'],
            ['Rezervasyon nasıl yapılır?', 'Rezervasyonlar yetkili acenta üzerinden, sadece ön ödemeli olarak alınır. WhatsApp, telefon veya bilgi formu ile bize ulaşabilirsiniz.', 'odeme'],
            ['Acenta üzerinden rezervasyon güvenli mi?', 'Evet. TÜRSAB üyesi yetkili acenta olarak çalışıyoruz. Tüm rezervasyon belgeleri ve fatura tarafımızdan düzenlenir.', 'odeme'],
            ['İptal ve iade koşulları nedir?', 'İptal koşulları paket türüne göre değişir. Detaylar rezervasyon onay belgenizde belirtilir.', 'odeme'],
            ['Otelde Wi-Fi var mı?', 'Tüm odalarda ve ortak alanlarda ücretsiz Wi-Fi mevcuttur.', 'oda'],
            ['Evcil hayvan kabul ediyor musunuz?', 'Üzgünüz, otelimiz evcil hayvan kabul etmemektedir.', 'genel'],
        ];

        foreach ($faqs as $i => [$question, $answer, $category]) {
            Faq::updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'category' => $category, 'sort_order' => $i, 'is_active' => true]
            );
        }
    }
}
