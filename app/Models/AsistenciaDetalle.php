<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsistenciaDetalle extends Model
{
    use HasFactory;

    protected $table = 'asistencia_detalle';
    protected $primaryKey = 'id_asistencia_detalle';
    public $timestamps = false;

    protected $fillable = [
        'id_asistencia_clase',
        'id_inscripcion',
        'estado_asistencia',
        'observacion'
    ];

    protected $casts = [
        'estado_asistencia' => 'string'
    ];

    // Relaciones
    public function asistenciaClase()
    {
        return $this->belongsTo(AsistenciaClase::class, 'id_asistencia_clase');
    }

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }
}
