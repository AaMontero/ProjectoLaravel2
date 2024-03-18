<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserAction;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;



class RolController extends Controller
{
    public function index()

    {
        // Obtener detalles del usuario desde la base de datos 
        $user = User::all();
        $roles = Role::all();
        return view('roles.rol', ['user' => $user, 'roles' => $roles]);
    }


    public function asignarRol(Request $request, User $user)
    {
        try {
            $rolId = $request->input('rol_id');

            // Asignar el rol al usuario
            $user->syncRoles([$rolId]);

            // Crear un registro en la tabla de registros
            UserAction::create([
                'user_id' => $request->user()->id,
                'action' => 'asignar rol', // Acción de asignar rol
                'entity_type' => 'user', // Tipo de entidad
                'entity_id' => $user->id, // ID del usuario al que se le asigna el rol
                'modified_data' => json_encode(['rol_id' => $rolId]), // Datos modificados

            ]);

            return redirect()->route('roles.rol')->with('status', 'Rol asignado exitosamente');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al asignar el rol')->withInput();
        }
    }
}
