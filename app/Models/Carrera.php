<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // Relaciones
    public function cuposGestiones()
    {
        return $this->hasMany(CupoCarreraGestion::class, 'id_carrera');
    }

    public function postulantesOpcionPrimera()
    {
        return $this->hasMany(Postulante::class, 'id_carrera_primera_opcion');
    }

    public function postulantesOpcionSegunda()
    {
        return $this->hasMany(Postulante::class, 'id_carrera_segunda_opcion');
    }

    public function resultadosAdmision()
    {
        return $this->hasMany(ResultadoAdmision::class, 'id_carrera_admitida');
    }
}
