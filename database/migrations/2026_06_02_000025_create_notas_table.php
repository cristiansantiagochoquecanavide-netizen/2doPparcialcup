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
        Schema::create('notas', function (Blueprint $table) {
            $table->bigIncrements('id_nota');
            // CU20: inscripcion existente.
            $table->unsignedBigInteger('id_inscripcion');
            // CU20: evaluacion configurada existente.
            $table->unsignedBigInteger('id_evaluacion');
            // CU20: nota validada entre 0 y 100.
            $table->decimal('nota', 5, 2);
            $table->timestamp('fecha_registro')->useCurrent();
            // CU20: evita duplicar nota para la misma inscripcion y evaluacion.
            $table->unique(['id_inscripcion', 'id_evaluacion']);
            $table->foreign('id_inscripcion')->references('id_inscripcion')->on('inscripciones')->onDelete('cascade');
            $table->foreign('id_evaluacion')->references('id_evaluacion')->on('evaluacion_config')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notas');
    }
};
