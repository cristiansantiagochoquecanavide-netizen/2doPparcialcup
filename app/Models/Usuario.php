<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Usuario.
 * Representa la tabla usuarios y participa en CU1, CU2, CU3 y CU5.
 */
class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'nombre_usuario',
        'correo',
        'password_hash',
        'estado',
        'fecha_creacion'
    ];

    protected $hidden = [
        'password_hash'
    ];

    protected $casts = [
        'fecha_creacion' => 'datetime',
        'estado' => 'string'
    ];

    // Un usuario pertenece a un rol del sistema.
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    // Un usuario puede generar varios registros en bitacora.
    public function bitacoras()
    {
        return $this->hasMany(Bitacora::class, 'id_usuario');
    }

    // Un usuario puede cargar varios lotes de datos.
    public function lotesCargas()
    {
        return $this->hasMany(LoteCargaUsuario::class, 'cargado_por');
    }

    // Un usuario puede registrar asistencias de clases.
    public function asistenciasRegistradas()
    {
        return $this->hasMany(AsistenciaClase::class, 'registrado_por');
    }

    // Método para autenticación
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}
