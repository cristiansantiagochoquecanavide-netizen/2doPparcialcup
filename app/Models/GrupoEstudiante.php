<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo GrupoEstudiante.
 * Representa la tabla grupo_estudiante y participa en CU12.
 */
class GrupoEstudiante extends Model
{
    use HasFactory;

    protected $table = 'grupo_estudiante';
    protected $primaryKey = 'id_grupo_estudiante';
    public $timestamps = false;

    protected $fillable = [
        // CU13: el grupo debe estar activo y tener cupo disponible antes de asignar.
        'id_grupo',
        // CU13: la inscripcion debe existir y no debe estar asignada a otro grupo.
        'id_inscripcion',
        'fecha_asignacion'
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime'
    ];

    // Cada asignacion pertenece a un grupo.
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }

    // Cada asignacion referencia una inscripcion.
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }
}
