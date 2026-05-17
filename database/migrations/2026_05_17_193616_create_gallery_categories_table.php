<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 50)->unique();
            $table->string('name', 100);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Mevcut sabit kategorileri taşı
        $defaults = [
            ['slug' => 'havuz', 'name' => 'Havuz', 'sort_order' => 1],
            ['slug' => 'plaj', 'name' => 'Plaj', 'sort_order' => 2],
            ['slug' => 'oda', 'name' => 'Odalar', 'sort_order' => 3],
            ['slug' => 'yemek', 'name' => 'Yemek', 'sort_order' => 4],
            ['slug' => 'animasyon', 'name' => 'Animasyon', 'sort_order' => 5],
            ['slug' => 'dis_mekan', 'name' => 'Dış Mekan', 'sort_order' => 6],
        ];

        $now = now();
        foreach ($defaults as &$row) {
            $row['is_active'] = true;
            $row['created_at'] = $now;
            $row['updated_at'] = $now;
        }
        unset($row);

        DB::table('gallery_categories')->insert($defaults);
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_categories');
    }
};
