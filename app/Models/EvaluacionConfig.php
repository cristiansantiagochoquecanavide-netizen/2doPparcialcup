<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EvaluacionConfig extends Model
{
    use HasFactory;

    protected $table = 'evaluacion_config';
    protected $primaryKey = 'id_evaluacion';
    public $timestamps = false;

    protected $fillable = [
        'id_gestion',
        'id_materia',
        'numero_evaluacion',
        'porcentaje'
    ];

    protected $casts = [
        'numero_evaluacion' => 'integer',
        'porcentaje' => 'decimal:2'
    ];

    // Relaciones
    public function gestion()
    {
        return $this->belongsTo(GestionAcademica::class, 'id_gestion');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    public function notas()
    {
        return $this->hasMany(Nota::class, 'id_evaluacion');
    }
}
