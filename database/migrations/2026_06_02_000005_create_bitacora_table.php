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
        Schema::create('bitacora', function (Blueprint $table) {
            $table->bigIncrements('id_bitacora');
            $table->unsignedBigInteger('id_usuario');
            $table->timestamp('fecha_hora')->useCurrent();
            $table->string('modulo', 80);
            $table->string('accion', 80);
            $table->string('descripcion', 255)->nullable();
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora');
    }
};
