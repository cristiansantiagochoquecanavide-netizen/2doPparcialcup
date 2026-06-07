<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Rol.
 * Representa la tabla roles y participa en CU3 y CU4.
 */
class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';
    protected $primaryKey = 'id_rol';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado'
    ];

    protected $casts = [
        'estado' => 'string'
    ];

    // Un rol puede tener varios permisos asignados.
    public function permisos()
    {
        return $this->belongsToMany(Permiso::class, 'rol_permiso', 'id_rol', 'id_permiso');
    }

    // Un rol puede estar asignado a varios usuarios.
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_rol');
    }
}
