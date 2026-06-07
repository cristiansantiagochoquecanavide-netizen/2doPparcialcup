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
        Schema::create('asistencia_detalle', function (Blueprint $table) {
            $table->bigIncrements('id_asistencia_detalle');
            $table->unsignedBigInteger('id_asistencia_clase');
            $table->unsignedBigInteger('id_inscripcion');
            $table->string('estado_asistencia', 20);
            $table->string('observacion', 255)->nullable();
            $table->unique(['id_asistencia_clase', 'id_inscripcion']);
            $table->foreign('id_asistencia_clase')->references('id_asistencia_clase')->on('asistencia_clase')->onDelete('cascade');
            $table->foreign('id_inscripcion')->references('id_inscripcion')->on('inscripciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia_detalle');
    }
};
