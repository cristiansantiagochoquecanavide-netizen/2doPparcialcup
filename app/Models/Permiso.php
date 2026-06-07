<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Permiso.
 * Representa la tabla permisos y participa en CU4.
 */
class Permiso extends Model
{
    use HasFactory;

    protected $table = 'permisos';
    protected $primaryKey = 'id_permiso';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    // Un permiso puede estar asociado a varios roles.
    public function roles()
    {
        return $this->belongsToMany(Rol::class, 'rol_permiso', 'id_permiso', 'id_rol');
    }
}
