<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Nota extends Model
{
    use HasFactory;

    protected $table = 'notas';
    protected $primaryKey = 'id_nota';
    public $timestamps = false;

    protected $fillable = [
        'id_inscripcion',
        'id_evaluacion',
        'nota',
        'fecha_registro'
    ];

    protected $casts = [
        'nota' => 'decimal:2',
        'fecha_registro' => 'datetime'
    ];

    // Relaciones
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }

    public function evaluacion()
    {
        return $this->belongsTo(EvaluacionConfig::class, 'id_evaluacion');
    }
}
