<?php

use App\Models\Ciudad;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Ciudad::query()
            ->whereRaw('LOWER(nombre) LIKE ?', [Str::ascii('floridablanca').'%'])
            ->update([
                'latitud' => 7.0741091,
                'longitud' => -73.0907071,
            ]);
    }

    public function down(): void
    {
        Ciudad::query()
            ->whereRaw('LOWER(nombre) LIKE ?', [Str::ascii('floridablanca').'%'])
            ->update([
                'latitud' => null,
                'longitud' => null,
            ]);
    }
};
