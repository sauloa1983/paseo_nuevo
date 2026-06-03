<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_contacts', function (Blueprint $table) {
            $table->id();
            $table->integer('ciudad_id');
            $table->foreign('ciudad_id')->references('id')->on('ciudades')->cascadeOnDelete();
            $table->string('department');
            $table->string('email');
            $table->string('phones');
            $table->string('manager_name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('office_contacts');
    }
};
