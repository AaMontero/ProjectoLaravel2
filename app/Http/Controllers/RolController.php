<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;



class RolController extends Controller
{
    public function index()

    {
          // Obtener detalles del usuario desde la base de datos 
          $user = User::all();
          $roles = Role::all();

        //   return view('auth.register', compact('roles'));   
        //   return view('roles.rol', ['user' => $user]);
          
        return view('roles.rol', ['user' => $user, 'roles' => $roles]);

    }


    public function asignarRol(Request $request, User $user)
    {
    $rolId = $request->input('rol_id');

    // Asignar el rol al usuario
    $user->syncRoles([$rolId]);

    return redirect()->route('roles.rol')->with('status', 'Rol asignado exitosamente');
    }

}
