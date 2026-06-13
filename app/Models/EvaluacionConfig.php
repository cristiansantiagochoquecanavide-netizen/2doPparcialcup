<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo EvaluacionConfig.
 * Define las evaluaciones por materia y gestion.
 */
class EvaluacionConfig extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_config';
    protected $primaryKey = 'id_evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'id_gestion',
        'id_materia',
        // CU19: cada materia debe tener hasta 3 evaluaciones por gestion academica.
        'numero_evaluacion',
        // CU19: porcentaje valido para la evaluacion; debe validarse antes de guardar.
        'porcentaje'
    ];

    protected $casts = [
        'numero_evaluacion' => 'integer',
        'porcentaje' => 'decimal:2'
    ];

    // Relaciones
    public function gestion()
    {
        return $this->belongsTo(GestionAcademica::class, 'id_gestion');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function notas()
    {
        // CU20: cada evaluacion configurada recibe notas de estudiantes inscritos.
        return $this->hasMany(Nota::class, 'id_evaluacion');
    }
}
