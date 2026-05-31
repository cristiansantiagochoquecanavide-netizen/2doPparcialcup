<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsuarioImportado extends Model
{
    use HasFactory;

    protected $table = 'usuario_importado';
    protected $primaryKey = 'id_usuario_importado';
    public $timestamps = false;

    protected $fillable = [
        'id_lote',
        'nombre_completo',
        'correo',
        'rol_sugerido',
        'estado_generacion',
        'observacion'
    ];

    protected $casts = [
        'estado_generacion' => 'string'
    ];

    // Relaciones
    public function lote()
    {
        return $this->belongsTo(LoteCargaUsuario::class, 'id_lote');
    }
}
