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
        Schema::create('docentes', function (Blueprint $table) {
            $table->bigIncrements('id_docente');
            $table->string('ci', 20)->unique();
            $table->string('nombres', 80);
            $table->string('apellidos', 80);
            $table->string('telefono', 20)->nullable();
            $table->string('correo', 100)->unique()->nullable();
            $table->string('profesional_area', 100)->nullable();
            $table->boolean('tiene_maestria')->default(false);
            $table->boolean('tiene_diplomado_educacion_superior')->default(false);
            $table->string('estado_contratacion', 20)->default('ACTIVO');
            $table->timestamp('fecha_registro')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentes');
    }
};
