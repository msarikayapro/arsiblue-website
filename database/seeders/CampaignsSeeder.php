<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Seeder;

class CampaignsSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Bayrama Özel — aktif, ana kampanya (spec § 14)
        Campaign::updateOrCreate(
            ['title' => 'Bayrama Özel'],
            [
                'subtitle' => '3 gece, her şey dahil — sınırlı oda',
                'label_text' => 'Bayrama Özel',
                'old_price' => 36000,
                'new_price' => 16250,
                'currency' => 'TL',
                'nights' => 3,
                'adults' => 2,
                'children' => 1,
                'child_age_limit' => 12,
                'rooms_left' => 12,
                'urgency_text' => 'Son 12 oda',
                'countdown_enabled' => true,
                'valid_until' => '2026-05-31 23:59:59',
                'description' => 'Bayram tatili için tasarlanmış 3 gecelik her şey dahil aile paketi.',
                'included_items' => [
                    'Tüm yiyecek-içecek dahil (açık büfe)',
                    'Sınırsız alkollü/alkolsüz içecek (09:00-22:00)',
                    '3 havuz + aqua park dahil',
                    'Her gün animasyon ve şovlar',
                    'Otel şezlonglarımız ücretsiz',
                ],
                'is_active' => true,
                'show_on_homepage' => true,
                'visible_landings' => ['bayrama-ozel'],
                'sort_order' => 1,
            ]
        );

        // 2) Erken Rezervasyon — pasif, gelecek sezon kampanyası (user added)
        Campaign::updateOrCreate(
            ['title' => 'Erken Rezervasyon'],
            [
                'subtitle' => 'Yaz tatili için en uygun fiyat garantisi',
                'label_text' => 'Erken Rezervasyon',
                'old_price' => 4500,
                'new_price' => 3150,
                'currency' => 'TL',
                'nights' => 1,
                'adults' => 2,
                'children' => 0,
                'child_age_limit' => 12,
                'rooms_left' => null,
                'urgency_text' => 'Sınırlı sayıda — gece başı fiyat',
                'countdown_enabled' => false,
                'valid_until' => null,
                'description' => 'Yaz sezonu için erken rezervasyon avantajı.',
                'included_items' => [
                    'Tüm yiyecek-içecek dahil (açık büfe)',
                    'Sınırsız alkollü/alkolsüz içecek',
                    '3 havuz + aqua park',
                    'Animasyon ve şovlar',
                ],
                'is_active' => false, // gelecek sezon için pasif
                'show_on_homepage' => false,
                'visible_landings' => [],
                'sort_order' => 2,
            ]
        );
    }
}
