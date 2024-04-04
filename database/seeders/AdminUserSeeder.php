<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $adminUser = User::firstOrCreate([
            'name' => 'Administrador',
            'email' => 'travelqorig@gmail.com',
            'password' => Hash::make('UbOK6aaLtCiHjsu'),
        ]);
        // Asignar el rol de administrador al usuario
        $adminUser->assignRole('superAdmin');
    }
}
