<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->string('location')->nullable()->after('description');
            $table->integer('duration_minutes')->default(60)->after('location');
            $table->integer('included_persons')->default(1)->after('price');
            $table->decimal('extra_person_price', 10, 2)->default(0)->after('included_persons');
        });
    }

    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn(['location', 'duration_minutes', 'included_persons', 'extra_person_price']);
        });
    }
};