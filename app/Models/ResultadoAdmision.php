<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResultadoAdmision extends Model
{
    use HasFactory;

    protected $table = 'resultado_admision';
    protected $primaryKey = 'id_resultado';
    public $timestamps = false;

    protected $fillable = [
        'id_inscripcion',
        'promedio_final',
        'estado_resultado',
        'id_carrera_admitida',
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
