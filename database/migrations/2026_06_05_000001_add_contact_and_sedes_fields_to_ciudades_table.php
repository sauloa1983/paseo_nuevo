<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->string('telefono', 50)->nullable()->after('has_office');
            $table->string('direccion', 255)->nullable()->after('telefono');
            $table->string('matricula', 100)->nullable()->after('direccion');
            $table->boolean('tiene_multiples_sedes')->default(false)->after('matricula');
            $table->boolean('visible_buscador')->default(true)->after('tiene_multiples_sedes');
            $table->json('sedes')->nullable()->after('visible_buscador');
        });
    }

    public function down(): void
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->dropColumn([
                'telefono',
                'direccion',
                'matricula',
                'tiene_multiples_sedes',
                'visible_buscador',
                'sedes',
            ]);
        });
    }
};
