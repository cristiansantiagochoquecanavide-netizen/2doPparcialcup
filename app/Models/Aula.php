<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Aula.
 * Soporta CU16: gestionar aulas.
 */
class Aula extends Model
{
    use HasFactory;

    protected $table = 'aulas';
    protected $primaryKey = 'id_aula';
    public $timestamps = false;

    protected $fillable = [
        // CU16: codigo obligatorio y unico para registrar, editar, buscar y listar aulas.
        'codigo',
        'nombre',
        // CU16: capacidad debe validarse mayor a 0.
        'capacidad',
        'ubicacion',
        // CU16: estados esperados DISPONIBLE o NO DISPONIBLE.
        'estado'
    ];

    protected $casts = [
        'capacidad' => 'integer',
        'estado' => 'string'
    ];

    // CU17: un aula puede tener varias cargas horarias; deben evitarse cruces de horario.
    public function cargasHorarias()
    {
        return $this->hasMany(CargaHoraria::class, 'id_aula');
    }
}
