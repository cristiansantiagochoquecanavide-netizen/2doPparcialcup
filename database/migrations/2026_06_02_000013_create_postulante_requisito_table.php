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
        Schema::create('postulante_requisito', function (Blueprint $table) {
            $table->unsignedBigInteger('id_postulante');
            $table->unsignedBigInteger('id_requisito');
            $table->boolean('presentado')->default(false);
            $table->date('fecha_presentacion')->nullable();
            $table->string('observacion', 255)->nullable();
            $table->primary(['id_postulante', 'id_requisito']);
            $table->foreign('id_postulante')->references('id_postulante')->on('postulantes')->onDelete('cascade');
            $table->foreign('id_requisito')->references('id_requisito')->on('requisitos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulante_requisito');
    }
};
