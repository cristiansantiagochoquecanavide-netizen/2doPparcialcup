<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Bitacora.
 * Representa la tabla bitacora y participa en CU5 como registro de auditoria.
 */
class Bitacora extends Model
{
    use HasFactory;

    protected $table = 'bitacora';
    protected $primaryKey = 'id_bitacora';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'fecha_hora',
        'modulo',
        'accion',
        'descripcion'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime'
    ];

    // Cada registro de bitacora pertenece al usuario que ejecuto la accion.
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
