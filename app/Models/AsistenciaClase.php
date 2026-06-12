<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo AsistenciaClase.
 * Soporta CU18: registrar asistencia por clase y carga horaria.
 */
class AsistenciaClase extends Model
{
    use HasFactory;

    protected $table = 'asistencia_clase';
    protected $primaryKey = 'id_asistencia_clase';
    public $timestamps = false;

    protected $fillable = [
        // CU18: el docente registra asistencia segun su carga horaria asignada.
        'id_carga_horaria',
        // CU18: fecha de clase; no debe duplicarse para la misma carga horaria.
        'fecha_clase',
        // CU18: tema avanzado en la clase.
        'tema_avanzado',
        // CU18: usuario/docente que registra la asistencia.
        'registrado_por',
        'fecha_registro'
    ];

    protected $casts = [
        'fecha_clase' => 'date',
        'fecha_registro' => 'datetime'
    ];

    // CU18: desde la carga horaria se obtiene grupo, materia, docente y aula.
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
        // CU18: detalle individual de asistencia para cada estudiante del grupo.
        return $this->hasMany(AsistenciaDetalle::class, 'id_asistencia_clase');
    }
}
