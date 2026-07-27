<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // 1. Eliminamos la restricción antigua hacia la tabla 'brands'
            $table->dropForeign('articles_brand_id_foreign');

            // 2. Apuntamos 'brand_id' para que sea una llave foránea válida hacia 'users'
            $table->foreign('brand_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }
};