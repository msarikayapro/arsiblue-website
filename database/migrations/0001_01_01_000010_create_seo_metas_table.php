<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_metas', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->unique();
            $table->string('title', 70)->nullable();           // 60 max + buffer
            $table->string('description', 200)->nullable();    // 160 max + buffer
            $table->string('keywords')->nullable();
            $table->string('og_title', 70)->nullable();
            $table->string('og_description', 200)->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots', 50)->default('index,follow');
            $table->longText('schema_json')->nullable();       // custom JSON-LD
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_metas');
    }
};
