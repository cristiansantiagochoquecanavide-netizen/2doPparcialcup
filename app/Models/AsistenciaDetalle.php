<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo AsistenciaDetalle.
 * Guarda la asistencia individual de cada estudiante en una clase.
 */
class AsistenciaDetalle extends Model
{
    use HasFactory;

    protected $table = 'asistencia_detalle';
    protected $primaryKey = 'id_asistencia_detalle';
    public $timestamps = false;

    protected $fillable = [
        'id_asistencia_clase',
        // CU18: estudiante inscrito que pertenece al grupo de la carga horaria.
        'id_inscripcion',
        // CU18: estados esperados PRESENTE, AUSENTE, ATRASO o LICENCIA.
        'estado_asistencia',
        'observacion'
    ];

    protected $casts = [
        'estado_asistencia' => 'string'
    ];

    // CU18: relaciona el detalle con la clase registrada.
    public function asistenciaClase()
    {
        return $this->belongsTo(AsistenciaClase::class, 'id_asistencia_clase');
    }

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }
}
