<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->bigIncrements('id_materia');
            // CU15: nombre obligatorio y unico para evitar materias duplicadas.
            $table->string('nombre', 100)->unique();
            $table->string('descripcion', 200)->nullable();
            $table->string('estado', 20)->default('ACTIVA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
