<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Nota.
 * Registra las calificaciones de cada estudiante inscrito.
 */
class Nota extends Model
{
    use HasFactory;

    protected $table = 'notas';
    protected $primaryKey = 'id_nota';
    public $timestamps = false;

    protected $fillable = [
        // CU20: debe existir inscripcion antes de registrar o editar nota.
        'id_inscripcion',
        // CU20: debe existir evaluacion configurada.
        'id_evaluacion',
        // CU20: nota entre 0 y 100; no debe duplicarse para la misma inscripcion/evaluacion.
        'nota',
        'fecha_registro'
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'fecha_registro' => 'datetime'
    ];

    // CU20: el docente solo debe registrar notas de estudiantes vinculados a su carga horaria.
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }

    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionConfig::class, 'id_evaluacion');
    }
}
