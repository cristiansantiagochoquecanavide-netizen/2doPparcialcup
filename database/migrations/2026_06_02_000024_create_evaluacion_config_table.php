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
        Schema::create('evaluacion_config', function (Blueprint $table) {
            $table->bigIncrements('id_evaluacion');
            $table->unsignedBigInteger('id_gestion');
            $table->unsignedBigInteger('id_materia');
            $table->integer('numero_evaluacion');
            $table->decimal('porcentaje', 5, 2);
            $table->unique(['id_gestion', 'id_materia', 'numero_evaluacion']);
            $table->foreign('id_gestion')->references('id_gestion')->on('gestion_academica')->onDelete('cascade');
            $table->foreign('id_materia')->references('id_materia')->on('materias')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacion_config');
    }
};
