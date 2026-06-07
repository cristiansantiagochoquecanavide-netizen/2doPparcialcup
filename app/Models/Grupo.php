<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Modelo Grupo.
 * Representa la tabla grupos y participa en CU12 y CU14.
 */
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

    // Un grupo pertenece a una gestion academica.
    public function gestion()
    {
        return $this->belongsTo(GestionAcademica::class, 'id_gestion');
    }

    // Un grupo puede tener varias asignaciones de estudiantes.
    public function estudiantesGrupo()
    {
        return $this->hasMany(GrupoEstudiante::class, 'id_grupo');
    }

    // Un grupo puede tener varias cargas horarias asignadas.
    public function cargasHorarias()
    {
        return $this->hasMany(CargaHoraria::class, 'id_grupo');
    }
}
