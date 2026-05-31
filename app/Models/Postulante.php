<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // Relaciones
    public function carreraOpcionPrimera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_primera_opcion');
    }

    public function carreraOpcionSegunda()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera_segunda_opcion');
    }

    public function requisitos()
    {
        return $this->belongsToMany(Requisito::class, 'postulante_requisito', 'id_postulante', 'id_requisito')
                    ->withPivot('presentado', 'fecha_presentacion', 'observacion');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_postulante');
    }

    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'id_postulante');
    }
}
