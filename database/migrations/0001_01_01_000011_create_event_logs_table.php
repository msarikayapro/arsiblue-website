<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->id();
            $table->string('event_name', 50);  // lead|contact|whatsapp_click|phone_click|page_view|form_submit|...
            $table->string('event_id', 36)->nullable(); // Meta deduplication UUID

            $table->string('session_id', 64)->nullable();
            $table->string('user_ip', 45)->nullable();

            $table->string('country', 2)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('region', 80)->nullable();

            $table->string('device', 20)->nullable();  // mobile|tablet|desktop
            $table->string('browser', 50)->nullable();
            $table->string('os', 50)->nullable();

            $table->string('referrer', 500)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('utm_content', 100)->nullable();
            $table->string('utm_term', 100)->nullable();

            $table->string('landing_page', 255)->nullable();
            $table->string('current_page', 255)->nullable();

            $table->json('payload')->nullable();

            $table->boolean('fb_pixel_sent')->default(false);
            $table->boolean('fb_capi_sent')->default(false);
            $table->boolean('ga4_sent')->default(false);
            $table->boolean('gads_sent')->default(false);

            $table->timestamp('created_at')->useCurrent();

            $table->index('event_name');
            $table->index('created_at');
            $table->index('city');
            $table->index('device');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_logs');
    }
};
