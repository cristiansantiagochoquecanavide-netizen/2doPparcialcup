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
        Schema::create('aulas', function (Blueprint $table) {
            $table->bigIncrements('id_aula');
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 80);
            $table->integer('capacidad');
            $table->string('ubicacion', 100)->nullable();
            $table->string('estado', 20)->default('DISPONIBLE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
