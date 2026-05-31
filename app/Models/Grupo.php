<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';
    protected $primaryKey = 'id_grupo';
    public $timestamps = false;

    protected $fillable = [
        'id_gestion',
        'codigo_grupo',
        'cupo_maximo',
        'estado'
    ];

    protected $casts = [
        'cupo_maximo' => 'integer',
        'estado' => 'string'
    ];

    // Relaciones
    public function gestion()
    {
        return $this->belongsTo(GestionAcademica::class, 'id_gestion');
    }

    public function estudiantesGrupo()
    {
        return $this->hasMany(GrupoEstudiante::class, 'id_grupo');
    }

    public function cargasHorarias()
    {
        return $this->hasMany(CargaHoraria::class, 'id_grupo');
    }
}
