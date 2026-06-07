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
        Schema::create('asistencia_clase', function (Blueprint $table) {
            $table->bigIncrements('id_asistencia_clase');
            $table->unsignedBigInteger('id_carga_horaria');
            $table->date('fecha_clase');
            $table->string('tema_avanzado', 200)->nullable();
            $table->unsignedBigInteger('registrado_por');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->unique(['id_carga_horaria', 'fecha_clase']);
            $table->foreign('id_carga_horaria')->references('id_carga_horaria')->on('carga_horaria')->onDelete('cascade');
            $table->foreign('registrado_por')->references('id_usuario')->on('usuarios')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencia_clase');
    }
};
