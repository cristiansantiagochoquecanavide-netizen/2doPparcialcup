<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Materia.
 * Soporta CU15: gestionar materias del CUP.
 */
class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';
    protected $primaryKey = 'id_materia';
    public $timestamps = false;

    protected $fillable = [
        // CU15: materias obligatorias base: Computacion, Matematicas, Ingles y Fisica.
        // El nombre debe ser unico para evitar materias duplicadas.
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'string'
    ];

    // Las materias se conectan con horarios, docentes, aulas y grupos mediante carga_horaria.
    public function cargasHorarias()
    {
        return $this->hasMany(CargaHoraria::class, 'id_materia');
    }

    public function evaluacionesConfig()
    {
        // Cada materia del CUP tiene configuradas sus evaluaciones por gestion.
        return $this->hasMany(EvaluacionConfig::class, 'id_materia');
    }
}
