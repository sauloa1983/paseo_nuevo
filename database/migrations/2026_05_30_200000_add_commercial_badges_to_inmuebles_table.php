<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('inmuebles', 'contract_end_date')) {
            return;
        }

        $sqlMode = DB::selectOne('SELECT @@SESSION.sql_mode AS sql_mode')->sql_mode ?? '';

        DB::statement("SET SESSION sql_mode = ''");

        try {
            DB::statement('ALTER TABLE `inmuebles` ADD `contract_end_date` DATE NULL, ADD `badge_status` VARCHAR(255) NULL');
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$sqlMode]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inmuebles', function (Blueprint $table) {
            if (Schema::hasColumn('inmuebles', 'contract_end_date')) {
                $table->dropColumn('contract_end_date');
            }

            if (Schema::hasColumn('inmuebles', 'badge_status')) {
                $table->dropColumn('badge_status');
            }
        });
    }
};
