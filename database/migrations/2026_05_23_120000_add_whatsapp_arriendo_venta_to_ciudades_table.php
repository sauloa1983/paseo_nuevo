<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->string('whatsapp_arriendo', 20)->nullable()->after('whatsapp');
            $table->string('whatsapp_venta', 20)->nullable()->after('whatsapp_arriendo');
        });

        if (Schema::hasColumn('ciudades', 'whatsapp')) {
            foreach (DB::table('ciudades')->whereNotNull('whatsapp')->where('whatsapp', '!=', '')->get() as $ciudad) {
                DB::table('ciudades')
                    ->where('id', $ciudad->id)
                    ->update([
                        'whatsapp_arriendo' => $ciudad->whatsapp,
                        'whatsapp_venta' => $ciudad->whatsapp,
                    ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('ciudades', function (Blueprint $table) {
            $table->dropColumn(['whatsapp_arriendo', 'whatsapp_venta']);
        });
    }
};
