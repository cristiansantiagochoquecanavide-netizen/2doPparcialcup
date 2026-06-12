<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Pago.
 * Soporta el CU8: registrar pago de inscripcion asociado a un postulante.
 */
class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'id_postulante',
        // CU8: el monto debe validarse mayor a 0 antes de registrar el pago.
        'monto',
        // CU8: metodo_pago identifica efectivo, transferencia u otro medio definido por la facultad.
        'metodo_pago',
        // CU8: codigo_transaccion se registra cuando el metodo de pago lo requiere.
        'codigo_transaccion',
        // CU8: estados esperados PENDIENTE, PAGADO, RECHAZADO o ANULADO.
        'estado_pago',
        'fecha_pago'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'estado_pago' => 'string'
    ];

    // CU8: todo pago debe pertenecer a un postulante existente.
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante');
    }
}
