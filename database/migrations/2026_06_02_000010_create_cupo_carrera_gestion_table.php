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
        Schema::create('cupo_carrera_gestion', function (Blueprint $table) {
            $table->bigIncrements('id_cupo');
            $table->unsignedBigInteger('id_carrera');
            $table->unsignedBigInteger('id_gestion');
            // CU11: cupo maximo por carrera y gestion; la validacion evita valores negativos.
            $table->integer('cupo_maximo');
            // CU11: no puede existir mas de un cupo para la misma carrera en la misma gestion.
            $table->unique(['id_carrera', 'id_gestion']);
            $table->foreign('id_carrera')->references('id_carrera')->on('carreras')->onDelete('cascade');
            $table->foreign('id_gestion')->references('id_gestion')->on('gestion_academica')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupo_carrera_gestion');
    }
};
