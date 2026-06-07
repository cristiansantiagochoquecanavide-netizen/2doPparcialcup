<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo CargaHoraria.
 * Representa la tabla carga_horaria y participa en CU12 y CU14.
 */
class CargaHoraria extends Model
{
    use HasFactory;

    protected $table = 'carga_horaria';
    protected $primaryKey = 'id_carga_horaria';
    public $timestamps = false;

    protected $fillable = [
        'id_grupo',
        'id_materia',
        'id_docente',
        'id_aula',
        'dia_semana',
        'hora_inicio',
        'hora_fin'
    ];

    protected $casts = [
        'hora_inicio' => 'datetime:H:i:s',
        'hora_fin' => 'datetime:H:i:s'
    ];

    // Cada carga horaria pertenece a un grupo.
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }

    // Cada carga horaria pertenece a una materia.
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    // Cada carga horaria pertenece a un docente.
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    // Cada carga horaria se dicta en un aula.
    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

    // Una carga horaria puede generar varias asistencias de clase.
    public function asistenciasClase()
    {
        return $this->hasMany(AsistenciaClase::class, 'id_carga_horaria');
    }
}
