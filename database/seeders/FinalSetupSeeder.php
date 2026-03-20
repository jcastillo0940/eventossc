<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class FinalSetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Roles exist
        $roles = ['SuperAdmin', 'Digitador', 'Juez', 'Participante'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Create Users
        
        // SuperAdmin
        $superAdmin = User::updateOrCreate(
            ['email' => 'jeremy.castillo@supercarnes.com'],
            [
                'name' => 'Jeremy Castillo',
                'password' => Hash::make('Jeremy0940$'),
            ]
        );
        $superAdmin->syncRoles(['SuperAdmin']);

        // Digitador
        $digitador = User::updateOrCreate(
            ['email' => 'digitador@test.com'],
            [
                'name' => 'Digitador de Prueba',
                'password' => Hash::make('password'),
            ]
        );
        $digitador->syncRoles(['Digitador']);

        // Juez
        $juez = User::updateOrCreate(
            ['email' => 'juez@test.com'],
            [
                'name' => 'Juez de Prueba',
                'password' => Hash::make('password'),
                'bio' => 'Experto en parrilladas y eventos masivos.',
            ]
        );
        $juez->syncRoles(['Juez']);

        // Participante
        $participante = User::updateOrCreate(
            ['email' => 'participante@test.com'],
            [
                'name' => 'Participante de Prueba',
                'password' => Hash::make('password'),
            ]
        );
        $participante->syncRoles(['Participante']);
    }
}
