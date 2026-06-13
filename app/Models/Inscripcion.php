<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Inscripcion.
 * Soporta el CU9: formalizar la inscripcion despues de requisitos completos y pago confirmado.
 */
class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripciones';
    protected $primaryKey = 'id_inscripcion';
    public $timestamps = false;

    protected $fillable = [
        // CU9: debe validarse que el postulante exista y no tenga inscripcion duplicada en la gestion.
        'id_postulante',
        // CU9: la gestion academica debe estar activa al registrar la inscripcion.
        'id_gestion',
        'fecha_inscripcion',
        'estado_inscripcion'
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
        'estado_inscripcion' => 'string'
    ];

    // CU9: la inscripcion pertenece al postulante que cumplio requisitos y pago.
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
        // CU13: una inscripcion puede asignarse como maximo a un grupo en la gestion.
        return $this->hasOne(GrupoEstudiante::class, 'id_inscripcion');
    }

    public function asistenciasDetalle()
    {
        return $this->hasMany(AsistenciaDetalle::class, 'id_inscripcion');
    }

    public function notas()
    {
        // Cada estudiante inscrito registra notas por evaluacion de cada materia.
        return $this->hasMany(Nota::class, 'id_inscripcion');
    }

    public function resultadoAdmision()
    {
        return $this->hasOne(ResultadoAdmision::class, 'id_inscripcion');
    }
}
