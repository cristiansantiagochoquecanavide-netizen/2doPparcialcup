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
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->bigIncrements('id_inscripcion');
            // CU9: postulante validado con requisitos completos y pago confirmado.
            $table->unsignedBigInteger('id_postulante');
            // CU9: gestion academica activa para formalizar la inscripcion.
            $table->unsignedBigInteger('id_gestion');
            $table->timestamp('fecha_inscripcion')->useCurrent();
            $table->string('estado_inscripcion', 20)->default('INSCRITO');
            // CU9: evita inscripcion duplicada del mismo postulante en la misma gestion.
            $table->unique(['id_postulante', 'id_gestion']);
            $table->foreign('id_postulante')->references('id_postulante')->on('postulantes')->onDelete('cascade');
            $table->foreign('id_gestion')->references('id_gestion')->on('gestion_academica')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
