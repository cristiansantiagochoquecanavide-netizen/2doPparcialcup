<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoteCargaUsuario extends Model
{
    use HasFactory;

    protected $table = 'lote_carga_usuario';
    protected $primaryKey = 'id_lote';
    public $timestamps = false;

    protected $fillable = [
        'nombre_archivo',
        'tipo_archivo',
        'fecha_carga',
        'cargado_por',
        'estado'
    ];

    protected $casts = [
        'fecha_carga' => 'datetime',
        'estado' => 'string'
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'cargado_por', 'id_usuario');
    }

    public function usuariosImportados()
    {
        return $this->hasMany(UsuarioImportado::class, 'id_lote');
    }
}
