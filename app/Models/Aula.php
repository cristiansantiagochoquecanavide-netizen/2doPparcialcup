<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aula extends Model
{
    use HasFactory;

    protected $table = 'aulas';
    protected $primaryKey = 'id_aula';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'capacidad',
        'ubicacion',
        'estado'
    ];

    protected $casts = [
        'capacidad' => 'integer',
        'estado' => 'string'
    ];

    // Relaciones
    public function cargasHorarias()
    {
        return $this->hasMany(CargaHoraria::class, 'id_aula');
    }
}
