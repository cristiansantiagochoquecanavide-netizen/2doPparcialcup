<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GestionAcademica extends Model
{
    use HasFactory;

    protected $table = 'gestion_academica';
    protected $primaryKey = 'id_gestion';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'anio',
        'periodo',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    protected $casts = [
        'anio' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'estado' => 'string'
    ];

    // Relaciones
    public function cuposCarreras()
    {
        return $this->hasMany(CupoCarreraGestion::class, 'id_gestion');
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class, 'id_gestion');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_gestion');
    }

    public function evaluacionesConfig()
    {
        return $this->hasMany(EvaluacionConfig::class, 'id_gestion');
    }
}
