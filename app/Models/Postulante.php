<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Postulante.
 * Representa la tabla postulantes y participa en CU6 y CU7.
 */
class Postulante extends Model
{
    use HasFactory;

    protected $table = 'postulantes';
    protected $primaryKey = 'id_postulante';
    public $timestamps = false;

    protected $fillable = [
        'ci',
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'telefono',
        'correo',
        'colegio_procedencia',
        'ciudad',
        'titulo_bachiller',
        'otros_requisitos',
        'id_carrera_primera_opcion',
        'id_carrera_segunda_opcion',
        'fecha_registro',
        'estado'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'fecha_registro' => 'datetime',
        'sexo' => 'string',
        'estado' => 'string'
    ];

    // El postulante pertenece a una carrera como primera opcion.
    public function carreraOpcionPrimera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_primera_opcion');
    }

    // El postulante puede pertenecer a una carrera como segunda opcion.
    public function carreraOpcionSegunda()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_segunda_opcion');
    }

    // El postulante se valida contra varios requisitos documentales.
    public function requisitos()
    {
        return $this->belongsToMany(Requisito::class, 'postulante_requisito', 'id_postulante', 'id_requisito')
                    ->withPivot('presentado', 'fecha_presentacion', 'observacion');
    }

    // El postulante puede registrar varios pagos.
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_postulante');
    }

    // El postulante puede tener varias inscripciones academicas.
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_postulante');
    }
}
