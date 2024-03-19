<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $role1 = Role::create(['name' => 'Administrador']);
        $role2 = Role::create(['name' => 'Host']);
        $role3 = Role::create(['name' => 'Asesor']);
        $role3 = Role::create(['name' => 'superAdmin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
