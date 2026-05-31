<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // Relaciones
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

    public function asistenciasClase()
    {
        return $this->hasMany(AsistenciaClase::class, 'id_carga_horaria');
    }
}
