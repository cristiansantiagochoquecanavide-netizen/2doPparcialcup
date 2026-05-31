<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    public $timestamps = false;

    protected $fillable = [
        'id_postulante',
        'id_gestion',
        'fecha_inscripcion',
        'estado_inscripcion'
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
        'estado_inscripcion' => 'string'
    ];

    // Relaciones
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'id_postulante');
    }

    public function gestion()
    {
        return $this->belongsTo(GestionAcademica::class, 'id_gestion');
    }

    public function grupoEstudiante()
    {
        return $this->hasOne(GrupoEstudiante::class, 'id_inscripcion');
    }

    public function asistenciasDetalle()
    {
        return $this->hasMany(AsistenciaDetalle::class, 'id_inscripcion');
    }

    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_inscripcion');
    }

    public function resultadoAdmision()
    {
        return $this->hasOne(ResultadoAdmision::class, 'id_inscripcion');
    }
}
