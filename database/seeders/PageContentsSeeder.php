<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageContent;
use Illuminate\Database\Seeder;

/**
 * KIRMIZI ÇİZGİLER — bu copy spec § 16'ya uyar:
 *  - "yakın mesafede" (yürüme mesafesi DEĞİL)
 *  - "konforlu odalar" (metrekare YOK)
 *  - "müsaitliğe göre deniz manzaralı" (kesin vaat YOK)
 *  - "Sadece ön ödeme" (yüzde YOK)
 *  - "Yetkili acenta" (resmi otel sitesi DEĞİL)
 *  - Snack bar / çocuk kulübü / oda servisi: bahsedilmez.
 */
class PageContentsSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHome();
        $this->seedBayramaOzel();
        $this->seedBalayiPaketi();
        $this->seedAileOteli();
        $this->seedOdalar();
        $this->seedTesisler();
        $this->seedIletisim();
    }

    private function seedHome(): void
    {
        $page = Page::bySlug('home')->first();
        if (! $page) {
            return;
        }

        $sections = [
            ['hero_title', 'text', 'Akdeniz\'in Kalbinde, Ailenizle Unutulmaz Bir Tatil'],
            ['hero_subtitle', 'text', 'Alanya\'da denize sıfır, her şey dahil 4 yıldızlı aile oteli'],
            ['hero_image', 'image', ''],
            ['trust_strip', 'json', json_encode([
                'Denize Sıfır',
                'Her Şey Dahil',
                'Aile Oteli',
                'Sadece Ön Ödeme',
                '3 Havuz + Aqua Park',
            ], JSON_UNESCAPED_UNICODE)],
            ['about_title', 'text', 'Akdeniz\'in Mavisinde Huzur Dolu Bir Tatil'],
            ['about_text', 'html', '<p>Arsi Blue Beach Hotel, Alanya Mahmutlar\'da denize sıfır konumda 4 yıldızlı aile odaklı bir tatil otelidir. Konforlu odalar, geniş havuz alanları ve her şey dahil hizmetiyle ailenize huzurlu bir Akdeniz tatili sunar.</p>'],
            ['about_image', 'image', ''],
            ['why_us', 'json', json_encode([
                ['icon' => 'verified', 'title' => 'Yetkili Acenta', 'text' => 'Arsi Blue Beach\'in yetkili acentasıyız. Şeffaf süreç, hızlı yanıt.'],
                ['icon' => 'payments', 'title' => 'Sadece Ön Ödeme', 'text' => 'Tüm rezervasyonlar ön ödemeli olarak alınır. Saklı ücret yok.'],
                ['icon' => 'support_agent', 'title' => '7/24 Destek', 'text' => 'WhatsApp ve telefon hatlarımız her saat ulaşılabilir.'],
                ['icon' => 'card_membership', 'title' => 'TÜRSAB Belgeli', 'text' => 'Türkiye Seyahat Acentaları Birliği üyesi.'],
                ['icon' => 'family_restroom', 'title' => 'Aile Odaklı', 'text' => '%100 aile oteli — sakin, güvenli, çocuk dostu.'],
                ['icon' => 'local_offer', 'title' => 'En İyi Fiyat', 'text' => 'Resmi acenta fiyatı — aracı kar marjı yok.'],
            ], JSON_UNESCAPED_UNICODE)],
            ['final_cta_title', 'text', 'Ailenizle Tatile Hazır mısınız?'],
            ['final_cta_subtitle', 'text', 'Aşağıdan tercih ettiğiniz iletişim kanalını seçin, en kısa sürede dönüş yapalım.'],
        ];

        $this->upsert($page->id, $sections);
    }

    private function seedBayramaOzel(): void
    {
        $page = Page::bySlug('bayrama-ozel')->first();
        if (! $page) {
            return;
        }

        $this->upsert($page->id, [
            ['hero_title', 'text', 'Bayrama Özel Aile Tatili'],
            ['hero_subtitle', 'text', '3 gece konaklama, her şey dahil — sınırlı sayıda oda'],
            ['benefits_list', 'json', json_encode([
                'Tüm yiyecek-içecek dahil (açık büfe)',
                'Sınırsız alkollü/alkolsüz içecek (09:00–22:00)',
                '3 havuz + aqua park dahil',
                'Her gün animasyon ve şovlar',
                'Otel şezlonglarımız ücretsiz',
            ], JSON_UNESCAPED_UNICODE)],
            ['cta_text', 'text', 'Bayram tatiliniz için bilgi alın — sadece ön ödeme.'],
        ]);
    }

    private function seedBalayiPaketi(): void
    {
        $page = Page::bySlug('balayi-paketi')->first();
        if (! $page) {
            return;
        }

        $this->upsert($page->id, [
            ['hero_title', 'text', 'Balayınızı Akdeniz\'de Taçlandırın'],
            ['hero_subtitle', 'text', 'Romantik atmosfer, müsaitliğe göre deniz manzaralı odalar, özel sürprizler'],
            ['package_includes', 'json', json_encode([
                'Oda süslemesi (gül yaprakları, romantik kurulum)',
                'Karşılama meyve sepeti',
                'Karşılama şarabı (yerli)',
                'Müsaitliğe göre deniz manzaralı oda upgrade',
                'Geç çıkış (müsaitliğe göre)',
            ], JSON_UNESCAPED_UNICODE)],
            ['cta_text', 'text', 'Balayı paketi için bilgi alın — kişiye özel hazırlık.'],
        ]);
    }

    private function seedAileOteli(): void
    {
        $page = Page::bySlug('aile-oteli')->first();
        if (! $page) {
            return;
        }

        $this->upsert($page->id, [
            ['hero_title', 'text', 'Çocuklarınız İçin Güvenli, Sizin İçin Huzurlu'],
            ['hero_subtitle', 'text', '%100 aile oteli — aqua park, çocuk havuzu, animasyon'],
            ['family_features', 'json', json_encode([
                ['icon' => 'pool', 'title' => 'Çocuk Havuzu', 'text' => 'Çocuklara özel, gözetim altında havuz alanı.'],
                ['icon' => 'water', 'title' => 'Aqua Park', 'text' => 'Renkli kaydıraklarla eğlence garantili.'],
                ['icon' => 'celebration', 'title' => 'Her Gün Animasyon', 'text' => 'Çocuk ve yetişkinler için günlük etkinlikler.'],
                ['icon' => 'restaurant', 'title' => 'Açık Büfe', 'text' => 'Çocuk dostu mönü ve sağlıklı seçenekler.'],
            ], JSON_UNESCAPED_UNICODE)],
            ['safety_text', 'html', '<p>Arsi Blue Beach %100 aile oteli olarak konumlanmıştır. Sakin, güvenli ve çocuk dostu atmosferiyle aileler için ideal bir tatil noktasıdır.</p>'],
        ]);
    }

    private function seedOdalar(): void
    {
        $page = Page::bySlug('odalar')->first();
        if (! $page) {
            return;
        }

        $this->upsert($page->id, [
            ['hero_title', 'text', 'Yenilenmiş, Konforlu Odalarımız'],
            ['hero_subtitle', 'text', 'Standart, aile ve müsaitliğe göre deniz manzaralı oda seçenekleri'],
            ['intro_text', 'html', '<p>Tüm odalarımız konforlu döşeme, klima, TV, mini buzdolabı ve banyo amenities ile donatılmıştır.</p>'],
        ]);
    }

    private function seedTesisler(): void
    {
        $page = Page::bySlug('tesisler')->first();
        if (! $page) {
            return;
        }

        $this->upsert($page->id, [
            ['hero_title', 'text', 'Su, Eğlence, Lezzet — Her Şey Dahil'],
            ['hero_subtitle', 'text', '3 havuz, aqua park, mavi sahil ve günlük animasyon programı'],
            ['facilities', 'json', json_encode([
                ['icon' => 'pool', 'title' => 'Aqua Park & Yetişkin Havuzu', 'text' => 'Renkli kaydıraklar ve geniş yetişkin havuz alanı.'],
                ['icon' => 'child_care', 'title' => 'Çocuk Havuzu', 'text' => 'Güvenli, sığ çocuk havuzu.'],
                ['icon' => 'roofing', 'title' => 'Kapalı Havuz', 'text' => 'Yıl boyu açık kapalı havuz.'],
                ['icon' => 'beach_access', 'title' => 'Mavi Sahil', 'text' => 'Otele yakın mesafede berrak Akdeniz sahili.'],
            ], JSON_UNESCAPED_UNICODE)],
        ]);
    }

    private function seedIletisim(): void
    {
        $page = Page::bySlug('iletisim')->first();
        if (! $page) {
            return;
        }

        $this->upsert($page->id, [
            ['hero_title', 'text', 'Bize Ulaşın'],
            ['hero_subtitle', 'text', 'WhatsApp, telefon veya bilgi formu — istediğiniz kanaldan dönüş yapalım.'],
        ]);
    }

    /**
     * @param array<int, array{0:string,1:string,2:string}> $sections
     */
    private function upsert(int $pageId, array $sections): void
    {
        foreach ($sections as $i => [$key, $type, $content]) {
            PageContent::updateOrCreate(
                ['page_id' => $pageId, 'section_key' => $key],
                ['content_type' => $type, 'content' => $content, 'sort_order' => $i, 'is_active' => true]
            );
        }
    }
}
