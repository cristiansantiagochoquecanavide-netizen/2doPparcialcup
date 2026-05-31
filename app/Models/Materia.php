<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias';
    protected $primaryKey = 'id_materia';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'string'
    ];

    // Relaciones
    public function cargasHorarias()
    {
        return $this->hasMany(CargaHoraria::class, 'id_materia');
    }

    public function evaluacionesConfig()
    {
        return $this->hasMany(EvaluacionConfig::class, 'id_materia');
    }
}
