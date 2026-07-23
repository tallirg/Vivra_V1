<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Permitimos nulos al inicio para que no exploten tus 12 reservas de prueba que ya existen
            $table->date('booking_date')->nullable()->after('experience_id');
            $table->foreignId('schedule_id')->nullable()->after('booking_date')->constrained('article_schedules')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn(['booking_date', 'schedule_id']);
        });
    }
};