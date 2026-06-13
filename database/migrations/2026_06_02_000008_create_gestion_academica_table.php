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
        Schema::create('gestion_academica', function (Blueprint $table) {
            $table->bigIncrements('id_gestion');
            $table->string('nombre', 60);
            $table->integer('anio');
            $table->string('periodo', 20);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->string('estado', 20)->default('ACTIVA');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gestion_academica');
    }
};
