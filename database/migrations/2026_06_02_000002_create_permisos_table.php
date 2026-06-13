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
        Schema::create('permisos', function (Blueprint $table) {
            $table->bigIncrements('id_permiso');
            $table->string('nombre', 80)->unique();
            $table->string('descripcion', 150)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
