<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsistenciaClase extends Model
{
    use HasFactory;

    protected $table = 'asistencia_clase';
    protected $primaryKey = 'id_asistencia_clase';
    public $timestamps = false;

    protected $fillable = [
        'id_carga_horaria',
        'fecha_clase',
        'tema_avanzado',
        'registrado_por',
        'fecha_registro'
    ];

    protected $casts = [
        'fecha_clase' => 'date',
        'fecha_registro' => 'datetime'
    ];

    // Relaciones
    public function cargaHoraria()
    {
        return $this->belongsTo(CargaHoraria::class, 'id_carga_horaria');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'registrado_por', 'id_usuario');
    }

    public function asistenciasDetalle()
    {
        return $this->hasMany(AsistenciaDetalle::class, 'id_asistencia_clase');
    }
}
