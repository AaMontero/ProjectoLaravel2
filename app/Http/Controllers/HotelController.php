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
                'provincia' => ['nullable', 'min:3', 'max:255'],
                'ciudad' => ['required', 'min:3', 'max:255'],
                'hotel_nombre' => ['required', 'min:3', 'max:255'],
                'imagen_hotel' => ['required'],
                'num_h' => ['required', 'integer', 'min:1'],
                'num_camas' => ['required', 'integer', 'min:1'],
                'precio' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
                'servicios' => ['required', 'array'],
                'tipo_alojamiento' => ['required'],
                'opiniones' => ['required', 'array'], // Cambiado a array
                'opiniones.*' => ['string', 'min:3', 'max:255'], // Reglas para cada opinión
            ]);
    
            $imagePaths = [];
    
            if ($request->hasFile('imagen_hotel')) {
                foreach ($request->file('imagen_hotel') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $folderName = $request->input('hotel_nombre'); // Usar el nombre del hotel
                    $file->move('uploads/hoteles/' . $folderName, $filename); // Mover la imagen a la carpeta del hotel
                    $imagePaths[] = 'uploads/hoteles/' . $folderName . '/' . $filename; // Guardar la ruta completa de la imagen
                }
            }
            
            
            $validated['imagen_hotel'] = implode(',', $imagePaths);
    
            // Concatenar las opiniones separadas por comas
            $opiniones = implode(',', $validated['opiniones']);
            $validated['opiniones'] = $opiniones;

            // Concatenar los servicios separadas por comas
            $servicios = implode(',', $validated['servicios']);
            $validated['servicios'] = $servicios;
    
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
