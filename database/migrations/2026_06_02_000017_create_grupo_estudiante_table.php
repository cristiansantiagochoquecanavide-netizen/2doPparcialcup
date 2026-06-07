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
        Schema::create('grupo_estudiante', function (Blueprint $table) {
            $table->bigIncrements('id_grupo_estudiante');
            $table->unsignedBigInteger('id_grupo');
            $table->unsignedBigInteger('id_inscripcion');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->unique('id_inscripcion');
            $table->foreign('id_grupo')->references('id_grupo')->on('grupos')->onDelete('cascade');
            $table->foreign('id_inscripcion')->references('id_inscripcion')->on('inscripciones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_estudiante');
    }
};
