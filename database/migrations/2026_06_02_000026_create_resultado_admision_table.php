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
        Schema::create('resultado_admision', function (Blueprint $table) {
            $table->bigIncrements('id_resultado');
            $table->unsignedBigInteger('id_inscripcion')->unique();
            // CU21: promedio final general del postulante; >= 60 APROBADO, < 60 REPROBADO.
            $table->decimal('promedio_final', 5, 2);
            $table->string('estado_resultado', 20);
            // CU22: carrera admitida segun cupos de primera opcion o segunda opcion.
            $table->unsignedBigInteger('id_carrera_admitida')->nullable();
            // CU22: null si queda aprobado en espera por falta de cupo.
            $table->integer('orden_opcion_admitida')->nullable();
            $table->timestamp('fecha_resultado')->useCurrent();
            $table->foreign('id_inscripcion')->references('id_inscripcion')->on('inscripciones')->onDelete('cascade');
            $table->foreign('id_carrera_admitida')->references('id_carrera')->on('carreras')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultado_admision');
    }
};
