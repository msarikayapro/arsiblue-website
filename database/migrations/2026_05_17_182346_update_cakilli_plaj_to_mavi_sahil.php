<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tesislerPageId = DB::table('pages')->where('slug', 'tesisler')->value('id');

        if ($tesislerPageId) {
            // hero_subtitle: plain text replace
            DB::table('page_contents')
                ->where('page_id', $tesislerPageId)
                ->where('section_key', 'hero_subtitle')
                ->update([
                    'content' => DB::raw("REPLACE(content, 'çakıllı plaj', 'mavi sahil')"),
                ]);

            // facilities: JSON içinde iki ayrı replace (title + text)
            DB::table('page_contents')
                ->where('page_id', $tesislerPageId)
                ->where('section_key', 'facilities')
                ->update([
                    'content' => DB::raw("REPLACE(REPLACE(content, 'Çakıllı Plaj', 'Mavi Sahil'), 'Otele yakın mesafede temiz çakıllı plaj.', 'Otele yakın mesafede berrak Akdeniz sahili.')"),
                ]);
        }

        // SEO meta descriptions
        DB::table('seo_metas')
            ->where('description', 'like', '%çakıllı plaj%')
            ->update([
                'description' => DB::raw("REPLACE(description, 'çakıllı plaj', 'mavi sahil')"),
            ]);
    }

    public function down(): void
    {
        // Geri alma: tekrar "çakıllı plaj"a döndürmek istemiyoruz (ürün kararı). No-op.
    }
};
