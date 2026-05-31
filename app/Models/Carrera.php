<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Carrera.
 * Representa la tabla carreras y participa en CU6 y CU10.
 */
class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras';
    protected $primaryKey = 'id_carrera';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'estado'
    ];

    protected $casts = [
        'estado' => 'string'
    ];

    // Una carrera puede tener cupos configurados por gestion academica.
    public function cuposGestiones()
    {
        return $this->hasMany(CupoCarreraGestion::class, 'id_carrera');
    }

    // Una carrera puede ser primera opcion de varios postulantes.
    public function postulantesOpcionPrimera()
    {
        return $this->hasMany(Postulante::class, 'id_carrera_primera_opcion');
    }

    // Una carrera puede ser segunda opcion de varios postulantes.
    public function postulantesOpcionSegunda()
    {
        return $this->hasMany(Postulante::class, 'id_carrera_segunda_opcion');
    }

    // Una carrera puede aparecer como carrera admitida en resultados.
    public function resultadosAdmision()
    {
        return $this->hasMany(ResultadoAdmision::class, 'id_carrera_admitida');
    }
}
