<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['home', 'Ana Sayfa', 'home', 0],
            ['bayrama-ozel', 'Bayrama Özel', 'landing', 1],
            ['balayi-paketi', 'Balayı Paketi', 'landing', 2],
            ['aile-oteli', 'Aile Oteli', 'landing', 3],
            ['odalar', 'Odalarımız', 'custom', 4],         // user-added route
            ['tesisler', 'Tesisler & Aktiviteler', 'custom', 5], // user-added route
            ['galeri', 'Galeri', 'custom', 6],
            ['iletisim', 'İletişim', 'custom', 7],
            ['kvkk', 'KVKK Aydınlatma Metni', 'legal', 100],
            ['cerez', 'Çerez Politikası', 'legal', 101],
            ['hakkimizda', 'Hakkımızda', 'legal', 102],
        ];

        foreach ($pages as [$slug, $title, $template, $order]) {
            Page::updateOrCreate(
                ['slug' => $slug],
                ['title' => $title, 'template' => $template, 'sort_order' => $order, 'is_active' => true]
            );
        }
    }
}
