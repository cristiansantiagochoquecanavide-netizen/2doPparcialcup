<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Docente.
 * Representa la tabla docentes y participa en CU14.
 */
class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';
    protected $primaryKey = 'id_docente';
    public $timestamps = false;

    protected $fillable = [
        'ci',
        'nombres',
        'apellidos',
        'telefono',
        'correo',
        'profesional_area',
        'tiene_maestria',
        'tiene_diplomado_educacion_superior',
        'estado_contratacion',
        'fecha_registro'
    ];

    protected $casts = [
        'tiene_maestria' => 'boolean',
        'tiene_diplomado_educacion_superior' => 'boolean',
        'fecha_registro' => 'datetime',
        'estado_contratacion' => 'string'
    ];

    // Un docente puede tener varias cargas horarias asignadas.
    public function cargasHorarias()
    {
        return $this->hasMany(CargaHoraria::class, 'id_docente');
    }
}
