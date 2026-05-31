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
        'id_carrera',
        'id_gestion',
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
