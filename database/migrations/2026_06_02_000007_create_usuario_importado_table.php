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
        Schema::create('usuario_importado', function (Blueprint $table) {
            $table->bigIncrements('id_usuario_importado');
            $table->unsignedBigInteger('id_lote');
            $table->string('nombre_completo', 120);
            $table->string('correo', 100);
            $table->string('rol_sugerido', 50)->nullable();
            $table->string('estado_generacion', 30)->default('PENDIENTE');
            $table->string('observacion', 255)->nullable();
            $table->foreign('id_lote')->references('id_lote')->on('lote_carga_usuario')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_importado');
    }
};
