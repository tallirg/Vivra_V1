<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('article_schedules', function (Blueprint $table) {
            $table->id();
            // Llave foránea amarrada a la experiencia
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            
            // 1 = Lunes, 2 = Martes ... 7 = Domingo
            $table->integer('day_of_week'); 
            
            $table->time('start_time');
            $table->integer('stock')->default(1); // La capacidad máxima de este horario específico
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('article_schedules');
    }
};