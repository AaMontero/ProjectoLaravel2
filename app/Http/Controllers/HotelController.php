<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Models\UserAction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\File;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $hotel = Hotel::all();

        return view('hoteles.hotel', ['hotel' => $hotel]);
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

    public function edit(Hotel $hotel)
    {
        return view('hoteles.edit', ['hotel' => $hotel]);
    }

    public function update(Request $request, Hotel $hotel)
    {
        try {
            $validated = $request->validate([
                'pais' => ['nullable', 'min:3', 'max:255'],
                'provincia' => ['nullable', 'min:3', 'max:255'],
                'ciudad' => ['nullable', 'min:3', 'max:255'],
                'hotel_nombre' => ['nullable', 'min:3', 'max:255'],
                'imagen_hotel' => ['nullable',],
                'num_h' => ['nullable', 'integer', 'min:1'],
                'num_camas' => ['nullable', 'integer', 'min:1'],
                'precio' => ['nullable', 'numeric', 'min:0.01', 'max:9999.99'],
                'servicios' => ['array'],
                'tipo_alojamiento' => ['nullable',],
                'opiniones' => ['array'], // Cambiado a array
            ]);


            if ($request->hasFile('imagen_hotel')) {
                $folderName = $hotel->hotel_nombre;
                $folderPath = 'uploads/hoteles/' . $folderName;
                // Eliminar todos los archivos existentes en la carpeta
                File::cleanDirectory($folderPath);
                $imagePaths = [];
                foreach ($request->file('imagen_hotel') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $file->move($folderPath, $filename); // Mover la imagen a la carpeta del hotel
                    $imagePaths[] = 'uploads/hoteles/' . $folderName . '/' . $filename; // Guardar la ruta completa de la imagen
                }
                // Actualizar la lista de imágenes del hotel
                $validated['imagen_hotel'] = implode(',', $imagePaths);
            }

            // Concatenar las opiniones separadas por comas
            $opiniones = $hotel->opiniones ? explode(',', $hotel->opiniones) : [];
            if (!empty($validated['opiniones'])) {
                $opiniones = array_merge($opiniones, $validated['opiniones']);
                $opiniones = array_unique(array_filter($opiniones)); // Filtrar para eliminar valores nulos
            }
            $validated['opiniones'] = implode(',', $opiniones);

            // Concatenar los servicios separados por comas
            $servicios = $hotel->servicios ? explode(',', $hotel->servicios) : [];
            if (!empty($validated['servicios'])) {
                $servicios = array_merge($servicios, $validated['servicios']);
                $servicios = array_unique(array_filter($servicios)); // Filtrar para eliminar valores nulos
            }
            $validated['servicios'] = implode(',', $servicios);



            // Crear un registro en la tabla UserAction solo si hay datos modificados
            $originalData = $hotel->getOriginal();
            $modifiedData = [];
            foreach ($validated as $key => $value) {
                if ($originalData[$key] != $value) {
                    $modifiedData[$key] = $value;
                }
            }

            if (!empty($modifiedData)) {
                UserAction::create([
                    'user_id' => $request->user()->id,
                    'action' => 'editar',
                    'entity_type' => 'hotel',
                    'entity_id' => $hotel->id,
                    'modified_data' => json_encode($modifiedData),
                ]);
            }

            // Actualizar solo los campos que se proporcionan en el formulario
            $hotel->fill(array_filter($validated))->save();

            return to_route('hotel')
                ->with('status', __('Hotel actualiado exitosamente'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }


    public function destroy(Hotel $hotel)
    {
        // Guardar los datos del paquete antes de eliminarlo
        $hotelEliminado = $hotel->toArray();

        // Eliminar la carpeta que contiene las imágenes del paquete
        $folderName = 'uploads/hoteles/' . $hotel->hotel_nombre;
        if (File::exists($folderName)) {
            File::deleteDirectory($folderName);
        }

        // Crear un registro en la tabla UserAction antes de eliminar el paquete
        UserAction::create([
            'user_id' => auth()->id(),
            'action' => 'eliminar',
            'entity_type' => 'paquete',
            'entity_id' => $hotel->id,
            'modified_data' => json_encode($hotelEliminado),
            // Otros campos relevantes que desees registrar en el log
        ]);

        // Eliminar el paquete
        $hotel->delete();


        return redirect()->route('hotel')
            ->with('status', __('Package deleted successfully'));
    }
}
