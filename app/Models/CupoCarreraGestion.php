<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo CupoCarreraGestion.
 * Representa la tabla cupo_carrera_gestion y participa en CU10.
 */
class CupoCarreraGestion extends Model
{
    use HasFactory;

    protected $table = 'cupo_carrera_gestion';
    protected $primaryKey = 'id_cupo';
    public $timestamps = false;

    protected $fillable = [
        // CU11: carrera existente a la que se asigna cupo.
        'id_carrera',
        // CU11: gestion academica existente.
        'id_gestion',
        // CU11: cupo maximo por carrera y gestion; debe validarse como no negativo.
        'cupo_maximo'
    ];

    protected $casts = [
        'cupo_maximo' => 'integer'
    ];

    // Cada cupo pertenece a una carrera.
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }

    // Cada cupo pertenece a una gestion academica.
    public function gestion()
    {
        return $this->belongsTo(GestionAcademica::class, 'id_gestion');
    }
}
