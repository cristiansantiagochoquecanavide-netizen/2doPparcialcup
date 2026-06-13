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
        Schema::create('requisitos', function (Blueprint $table) {
            $table->bigIncrements('id_requisito');
            $table->string('nombre', 100)->unique();
            $table->string('descripcion', 200)->nullable();
            $table->boolean('obligatorio')->default(true);
            $table->string('estado', 20)->default('ACTIVO');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitos');
    }
};
