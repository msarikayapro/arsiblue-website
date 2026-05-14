<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('phone', 30);
            $table->text('message')->nullable();

            $table->string('landing_page', 255)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();

            $table->string('user_ip', 45)->nullable();
            $table->string('user_agent', 500)->nullable();

            $table->string('status', 20)->default('yeni'); // yeni|aranildi|sonuclandi|iptal
            $table->text('notes')->nullable();

            $table->foreignId('event_log_id')->nullable()
                ->constrained('event_logs')->nullOnDelete();

            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
