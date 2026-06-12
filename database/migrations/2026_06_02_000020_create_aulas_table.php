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
        Schema::create('aulas', function (Blueprint $table) {
            $table->bigIncrements('id_aula');
            // CU16: codigo unico para registrar, editar, buscar y listar aulas sin duplicados.
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 80);
            // CU16: capacidad debe validarse mayor a 0.
            $table->integer('capacidad');
            $table->string('ubicacion', 100)->nullable();
            // CU16: estados esperados DISPONIBLE o NO DISPONIBLE.
            $table->string('estado', 20)->default('DISPONIBLE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
