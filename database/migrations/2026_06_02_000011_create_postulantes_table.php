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
        Schema::create('postulantes', function (Blueprint $table) {
            $table->bigIncrements('id_postulante');
            $table->string('ci', 20)->unique();
            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->date('fecha_nacimiento');
            $table->char('sexo', 1);
            $table->string('direccion', 150)->nullable();
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->unique()->nullable();
            $table->string('colegio_procedencia', 120)->nullable();
            $table->string('ciudad', 80)->nullable();
            $table->string('titulo_bachiller', 120)->nullable();
            $table->string('otros_requisitos', 255)->nullable();
            $table->unsignedBigInteger('id_carrera_primera_opcion');
            $table->unsignedBigInteger('id_carrera_segunda_opcion')->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('estado', 20)->default('REGISTRADO');
            $table->foreign('id_carrera_primera_opcion')->references('id_carrera')->on('carreras')->onDelete('restrict');
            $table->foreign('id_carrera_segunda_opcion')->references('id_carrera')->on('carreras')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulantes');
    }
};
