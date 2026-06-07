<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
    public $timestamps = false;

    protected $fillable = [
        'id_postulante',
        'monto',
        'metodo_pago',
        'codigo_transaccion',
        'estado_pago',
        'fecha_pago'
    ];

    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'estado_pago' => 'string'
    ];

    // Relaciones
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante');
    }
}
