<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (! Schema::hasColumn('testimonials', 'descripcion')) {
                $table->string('descripcion')->nullable()->after('nombre');
            }

            if (! Schema::hasColumn('testimonials', 'calificacion')) {
                $table->unsignedTinyInteger('calificacion')->nullable()->after('descripcion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('testimonials', function (Blueprint $table) {
            if (Schema::hasColumn('testimonials', 'calificacion')) {
                $table->dropColumn('calificacion');
            }

            if (Schema::hasColumn('testimonials', 'descripcion')) {
                $table->dropColumn('descripcion');
            }
        });
    }
};

