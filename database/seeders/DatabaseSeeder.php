<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SettingsSeeder::class,
            PagesSeeder::class,
            PageContentsSeeder::class,
            CampaignsSeeder::class,
            RoomsSeeder::class,
            FaqsSeeder::class,
            SeoMetasSeeder::class,
        ]);
    }
}
