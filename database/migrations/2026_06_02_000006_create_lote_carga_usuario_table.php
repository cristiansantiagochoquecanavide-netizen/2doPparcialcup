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
        Schema::create('lote_carga_usuario', function (Blueprint $table) {
            $table->bigIncrements('id_lote');
            $table->string('nombre_archivo', 150);
            $table->string('tipo_archivo', 30);
            $table->timestamp('fecha_carga')->useCurrent();
            $table->unsignedBigInteger('cargado_por');
            $table->string('estado', 20)->default('PENDIENTE');
            $table->foreign('cargado_por')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote_carga_usuario');
    }
};
