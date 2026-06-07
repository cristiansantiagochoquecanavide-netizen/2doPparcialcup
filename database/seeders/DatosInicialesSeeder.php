<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Permiso;
use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatosInicialesSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear Roles (o encontrar si ya existen)
        $rolAdmin = Rol::firstOrCreate(
            ['nombre' => 'Administrador'],
            [
                'descripcion' => 'Acceso total al sistema',
                'estado' => 'ACTIVO'
            ]
        );

        $rolCoordinador = Rol::firstOrCreate(
            ['nombre' => 'Coordinador académico'],
            [
                'descripcion' => 'Coordina aspectos académicos del CUP',
                'estado' => 'ACTIVO'
            ]
        );

        $rolDocente = Rol::firstOrCreate(
            ['nombre' => 'Docente'],
            [
                'descripcion' => 'Personal académico que enseña',
                'estado' => 'ACTIVO'
            ]
        );

        $rolAutoridad = Rol::firstOrCreate(
            ['nombre' => 'Autoridad académica'],
            [
                'descripcion' => 'Personal directivo del CUP',
                'estado' => 'ACTIVO'
            ]
        );

        // Crear Permisos (o encontrar si ya existen)
        $permisos = [
            'gestionar_usuarios',
            'gestionar_roles',
            'ver_bitacora',
            'gestionar_postulantes',
            'validar_requisitos',
            'gestionar_carreras',
            'gestionar_grupos',
            'gestionar_docentes'
        ];

        $permisosCreados = [];
        foreach ($permisos as $permiso) {
            $permisosCreados[] = Permiso::firstOrCreate(
                ['nombre' => $permiso],
                ['descripcion' => ucfirst(str_replace('_', ' ', $permiso))]
            )->id_permiso;
        }

        // Asignar todos los permisos al rol Administrador (sync actualiza la relación)
        $rolAdmin->permisos()->sync($permisosCreados);

        // Asignar permisos específicos a Coordinador académico
        $rolCoordinador->permisos()->sync([
            $permisosCreados[2], // ver_bitacora
            $permisosCreados[3], // gestionar_postulantes
            $permisosCreados[4], // validar_requisitos
            $permisosCreados[5], // gestionar_carreras
            $permisosCreados[6], // gestionar_grupos
        ]);

        // Asignar permisos específicos a Docente
        $rolDocente->permisos()->sync([
            $permisosCreados[3], // gestionar_postulantes
            $permisosCreados[4], // validar_requisitos
        ]);

        // Asignar permisos específicos a Autoridad académica
        $rolAutoridad->permisos()->sync([
            $permisosCreados[2], // ver_bitacora
            $permisosCreados[3], // gestionar_postulantes
            $permisosCreados[5], // gestionar_carreras
            $permisosCreados[6], // gestionar_grupos
        ]);

        // Crear usuario administrador (o encontrar si ya existe)
        $usuarioAdmin = Usuario::firstOrCreate(
            ['nombre_usuario' => 'admin'],
            [
                'correo' => 'admin@cupficct.com',
                'password_hash' => Hash::make('admin123'),
                'id_rol' => $rolAdmin->id_rol,
                'estado' => 'ACTIVO'
            ]
        );

        $this->command->info('Datos iniciales procesados exitosamente:');
        $this->command->info('- 4 Roles');
        $this->command->info('- 8 Permisos');
        $this->command->info('- 1 Usuario Administrador (admin / admin123)');
    }
}
