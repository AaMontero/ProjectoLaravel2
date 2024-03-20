<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
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
                'pais' => ['required', 'min:3', 'max:255'],
                'provincia' => ['min:3', 'max:255'],
                'ciudad' => ['required', 'min:3', 'max:255'],
                'nombre_hotel' => ['required', 'min:3', 'max:255'],
                'imagen_hotel' => ['required'],
                'num_h' => ['required', 'integer', 'min:1'],
                'num_camas' => ['required', 'integer', 'min:1'],
                'precio' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
                'servicios' => ['required'],
                'tipo_alojamiento' => ['required'],
                'opiniones' => ['required', 'min:0.01', 'max:9999.99'],
            ]);
    
            $imagePaths = [];

            if ($request->hasFile('imagen_hotel')) {
                foreach ($request->file('imagen_hotel') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $folderName = $request->input('nombre_hotel'); // Usar el nombre del hotel
                    $file->move('uploads/hoteles/' . $folderName, $filename); // Mover la imagen a la carpeta del hotel
                    $imagePaths[] = 'uploads/hoteles/' . $folderName . '/' . $filename; // Guardar la ruta completa de la imagen
                }
            }
            
    
            $validated['imagen_hotel'] = implode(',', $imagePaths);
            $hotel = Hotel::create($validated);

            // Crear un registro en la tabla UserAction
            UserAction::create([
                'user_id' => $request->user()->id,
                'action' => 'crear', // Acción de creación
                'entity_type' => 'hotel', // Tipo de entidad
                'entity_id' => $hotel->id,
            ]);
    
            return redirect()->route('hotel')
                ->with('status', __('Insertion done successfully'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }
    
}
