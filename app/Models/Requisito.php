<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Requisito extends Model
{
    use HasFactory;

    protected $table = 'requisitos';
    protected $primaryKey = 'id_requisito';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'obligatorio',
        'estado'
    ];

    protected $casts = [
        'obligatorio' => 'boolean',
        'estado' => 'string'
    ];

    // Relaciones
    public function postulantes()
    {
        return $this->belongsToMany(Postulante::class, 'postulante_requisito', 'id_requisito', 'id_postulante')
                    ->withPivot('presentado', 'fecha_presentacion', 'observacion');
    }
}
