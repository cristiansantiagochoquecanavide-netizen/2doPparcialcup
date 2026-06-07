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
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id_pago');
            $table->unsignedBigInteger('id_postulante');
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago', 30);
            $table->string('codigo_transaccion', 50)->nullable();
            $table->string('estado_pago', 20)->default('PENDIENTE');
            $table->timestamp('fecha_pago')->useCurrent();
            $table->foreign('id_postulante')->references('id_postulante')->on('postulantes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
