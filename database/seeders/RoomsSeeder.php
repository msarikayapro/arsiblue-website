<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomsSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Standart Oda',
                'slug' => 'standart-oda',
                'short_description' => '2 yetişkin için konforlu standart oda',
                'long_description' => '<p>Konforlu çift kişilik yatak, klima, TV, mini buzdolabı ve banyo amenities ile donatılmıştır. Aileler için ek yatak seçeneği mevcuttur.</p>',
                'features' => ['bed_double', 'ac', 'tv', 'wifi', 'fridge', 'safe', 'balcony'],
                'availability_note' => 'Müsaitliğe göre',
                'sort_order' => 1,
            ],
            [
                'name' => 'Aile Odası',
                'slug' => 'aile-odasi',
                'short_description' => '2 yetişkin + 2 çocuk için geniş aile odası',
                'long_description' => '<p>Geniş yaşam alanı, 2 ayrı yatak düzeni, klima, TV, mini buzdolabı, banyo amenities. Çocuklu aileler için tasarlandı.</p>',
                'features' => ['bed_double', 'bed_single', 'ac', 'tv', 'wifi', 'fridge', 'safe', 'balcony', 'family_friendly'],
                'availability_note' => 'Müsaitliğe göre',
                'sort_order' => 2,
            ],
            [
                'name' => 'Deniz Manzaralı Oda',
                'slug' => 'deniz-manzarali-oda',
                'short_description' => 'Müsaitliğe göre deniz manzaralı konforlu oda',
                'long_description' => '<p>Müsaitlik durumuna göre Akdeniz manzaralı, konforlu oda. Çift kişilik yatak, klima, TV, mini buzdolabı, banyo amenities.</p>',
                'features' => ['bed_double', 'ac', 'tv', 'wifi', 'fridge', 'safe', 'balcony', 'sea_view'],
                'availability_note' => 'Müsaitliğe göre — talepleriniz öncelikli alınır',
                'sort_order' => 3,
            ],
        ];

        foreach ($rooms as $data) {
            Room::updateOrCreate(['slug' => $data['slug']], $data + ['is_active' => true]);
        }
    }
}
