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
        Schema::create('grupos', function (Blueprint $table) {
            $table->bigIncrements('id_grupo');
            $table->unsignedBigInteger('id_gestion');
            $table->string('codigo_grupo', 20);
            $table->integer('cupo_maximo')->default(70);
            $table->string('estado', 20)->default('ACTIVO');
            $table->unique(['id_gestion', 'codigo_grupo']);
            $table->foreign('id_gestion')->references('id_gestion')->on('gestion_academica')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
