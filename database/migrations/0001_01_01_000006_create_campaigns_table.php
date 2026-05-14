<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('label_text', 50)->nullable(); // "Bayrama Özel", "Erken Rezervasyon" vb.

            $table->unsignedInteger('old_price')->nullable();
            $table->unsignedInteger('new_price')->nullable();
            $table->string('currency', 5)->default('TL');

            $table->unsignedTinyInteger('nights')->nullable();
            $table->unsignedTinyInteger('adults')->nullable();
            $table->unsignedTinyInteger('children')->default(0);
            $table->unsignedTinyInteger('child_age_limit')->nullable();

            $table->unsignedInteger('rooms_left')->nullable();
            $table->string('urgency_text')->nullable();

            $table->boolean('countdown_enabled')->default(false);
            $table->dateTime('valid_until')->nullable();

            $table->text('description')->nullable();
            $table->json('included_items')->nullable();   // string[]

            $table->string('hero_image')->nullable();
            $table->json('gallery_images')->nullable();   // string[]

            $table->boolean('is_active')->default(true);
            $table->boolean('show_on_homepage')->default(true);
            $table->json('visible_landings')->nullable(); // landing slug[]

            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'show_on_homepage']);
            $table->index('valid_until');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
