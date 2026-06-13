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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->bigIncrements('id_usuario');
            $table->unsignedBigInteger('id_rol');
            $table->string('nombre_usuario', 50)->unique();
            $table->string('correo', 100)->unique();
            $table->string('password_hash', 255);
            $table->string('estado', 20)->default('ACTIVO');
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->foreign('id_rol')->references('id_rol')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
