<?php

namespace App\Http\Controllers;

use App\Models\UserAction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class HotelController extends Controller
{
    public function index(Request $request)
    {
      
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre_hotel' => ['required', 'min:3', 'max:255'],
                'imagen_hotel' => ['required'],
                'num_h' => ['required', 'integer', 'min:1'],
                'num_camas' => ['required', 'integer', 'min:1'],
                'precio' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
                'servicios' => ['required', 'min:0.01', 'max:9999.99'],
                'tipo_alojamiento' => ['required', 'max:255'],
                'opiniones' => ['required', 'min:0.01', 'max:9999.99'],
            ]);

           


            // Crear un registro en la tabla UserAction
            UserAction::create([
                'user_id' => $request->user()->id,
                'action' => 'crear', // Acción de creación
                'entity_type' => 'paquete', // Tipo de entidad
                
            ]);

            return to_route('paquetes.paquetes')
                ->with('status',  __('Insertion done successfully'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }
}
