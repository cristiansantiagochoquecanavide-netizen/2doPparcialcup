<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrupoEstudiante extends Model
{
    use HasFactory;

    protected $table = 'grupo_estudiante';
    protected $primaryKey = 'id_grupo_estudiante';
    public $timestamps = false;

    protected $fillable = [
        'id_grupo',
        'id_inscripcion',
        'fecha_asignacion'
    ];

    protected $casts = [
        'fecha_asignacion' => 'datetime'
    ];

    // Relaciones
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }
}
