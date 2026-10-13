<?php

namespace Database\Seeders;

use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Database\Seeder;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolAdmin = Rol::where('nombre', 'Administrador')->first();

        Usuario::firstOrCreate(
            ['email' => 'admin@elparchedetodos.com'],
            [
                'nombre' => 'Administrador',
                'password' => 'Admin12345',
                'estado' => 'activo',
                'roles_id' => $rolAdmin->id,
            ]
        );
    }
}
