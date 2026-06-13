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
            // CU8: el pago siempre se asocia a un postulante existente.
            $table->unsignedBigInteger('id_postulante');
            // CU8: el controlador debe validar monto mayor a 0.
            $table->decimal('monto', 10, 2);
            $table->string('metodo_pago', 30);
            // CU8: codigo de transaccion opcional para transferencia/deposito u otros medios.
            $table->string('codigo_transaccion', 50)->nullable();
            // CU8: estados esperados PENDIENTE, PAGADO, RECHAZADO o ANULADO.
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
