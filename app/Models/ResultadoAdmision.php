<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo ResultadoAdmision.
 * Almacena el promedio final y el resultado APROBADO/REPROBADO.
 */
class ResultadoAdmision extends Model
{
    use HasFactory;

    protected $table = 'resultado_admision';
    protected $primaryKey = 'id_resultado';
    public $timestamps = false;

    protected $fillable = [
        'id_inscripcion',
        // CU21: en esta estructura el resultado es general por inscripcion/postulante.
        // La formula base es Promedio = (Nota1 + Nota2 + Nota3) / 3; si se calcula por materia,
        // el promedio_final debe representar el promedio general consolidado del postulante.
        // APROBADO si promedio >= 60; REPROBADO si promedio < 60.
        'promedio_final',
        'estado_resultado',
        // CU22: solo postulantes APROBADOS reciben carrera admitida si hay cupo disponible.
        // Primero se revisa la primera opcion; si esta llena, se revisa la segunda opcion.
        'id_carrera_admitida',
        // CU22: 1 para primera opcion, 2 para segunda opcion; null si queda en espera sin cupo.
        'orden_opcion_admitida',
        'fecha_resultado'
    ];

    protected $casts = [
        'promedio_final' => 'decimal:2',
        'estado_resultado' => 'string',
        'orden_opcion_admitida' => 'integer',
        'fecha_resultado' => 'datetime'
    ];

    // Relaciones
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }

    public function carreraAdmitida()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_admitida');
    }
}
