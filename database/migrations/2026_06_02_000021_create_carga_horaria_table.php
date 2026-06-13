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
        Schema::create('carga_horaria', function (Blueprint $table) {
            $table->bigIncrements('id_carga_horaria');
            // CU17: deben existir grupo, materia, docente y aula.
            $table->unsignedBigInteger('id_grupo');
            $table->unsignedBigInteger('id_materia');
            $table->unsignedBigInteger('id_docente');
            $table->unsignedBigInteger('id_aula');
            // CU17: dia y rango horario usados para evitar cruces de aula y docente.
            $table->string('dia_semana', 15);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->foreign('id_grupo')->references('id_grupo')->on('grupos')->onDelete('cascade');
            $table->foreign('id_materia')->references('id_materia')->on('materias')->onDelete('restrict');
            $table->foreign('id_docente')->references('id_docente')->on('docentes')->onDelete('restrict');
            $table->foreign('id_aula')->references('id_aula')->on('aulas')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carga_horaria');
    }
};
