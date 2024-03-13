<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class AdminUserSeeder extends Seeder
{
   
    public function run()
    {
        // Crear un usuario administrador
        // Comando para ejeccutar el seeder = php artisan db:seed --class=AdminUserSeeder
        $adminUser = User::firstOrCreate([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
        ]);

        // Asignar el rol de administrador al usuario
        $adminUser->assignRole('superAdmin');
    }
}
