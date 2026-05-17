<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $homePageId = DB::table('pages')->where('slug', 'home')->value('id');
        if (! $homePageId) {
            return;
        }

        // Zaten varsa tekrar ekleme (idempotent)
        $exists = DB::table('page_contents')
            ->where('page_id', $homePageId)
            ->where('section_key', 'about_image')
            ->exists();
        if ($exists) {
            return;
        }

        // about_text'in sort_order'ından hemen sonraya yerleştir
        $aboutTextOrder = DB::table('page_contents')
            ->where('page_id', $homePageId)
            ->where('section_key', 'about_text')
            ->value('sort_order');

        if ($aboutTextOrder !== null) {
            // about_text'ten sonraki tüm satırları bir kaydır
            DB::table('page_contents')
                ->where('page_id', $homePageId)
                ->where('sort_order', '>', $aboutTextOrder)
                ->increment('sort_order');

            $newOrder = $aboutTextOrder + 1;
        } else {
            // about_text yoksa en sona ekle
            $newOrder = (int) DB::table('page_contents')
                ->where('page_id', $homePageId)
                ->max('sort_order') + 1;
        }

        DB::table('page_contents')->insert([
            'page_id' => $homePageId,
            'section_key' => 'about_image',
            'content_type' => 'image',
            'content' => '',
            'sort_order' => $newOrder,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        $homePageId = DB::table('pages')->where('slug', 'home')->value('id');
        if (! $homePageId) {
            return;
        }

        DB::table('page_contents')
            ->where('page_id', $homePageId)
            ->where('section_key', 'about_image')
            ->delete();
    }
};
