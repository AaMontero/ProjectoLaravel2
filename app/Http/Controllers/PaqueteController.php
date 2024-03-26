<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\CaracteristicaPaquete;
use App\Models\UserAction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\File;

class PaqueteController extends Controller
{
    public function index(Request $request)
    {
        $num_dias = $request->num_dias;
        $num_noches = $request->num_noches;
        $precio_min = $request->precio_min;
        $precio_max = $request->precio_max;
        $afiliadoBool = ($request->socios == "socios") ? "precio_afiliado" : "precio_no_afiliado";
        $caracteristica = $request->caracteristica;
        if ($caracteristica == "") {
            return view('paquetes.paquetes', [
                "paquetes" => Paquete::with('user', 'incluye')
                    ->where('num_dias', 'LIKE', '%' . $num_dias . '%')
                    ->where('num_noches', 'LIKE', '%' . $num_noches . '%')
                    ->where($afiliadoBool, '>', ($precio_min != "") ? (float)$precio_min : 0)
                    ->where($afiliadoBool, '<', ($precio_max != "") ? (float)$precio_max : 999999999)
                    ->latest()->paginate(5),
                "num_dias" => $num_dias,
                "num_noches" => $num_noches,
                "precio_min" => $precio_min,
                "precio_max" => $precio_max,
                "socios" => $request->socios,
                "caracteristica" => $caracteristica
            ]);
        } else {
            return view('paquetes.paquetes', [
                "paquetes" => Paquete::with('user', 'incluye')
                    ->where('num_dias', 'LIKE', '%' . $num_dias . '%')
                    ->where('num_noches', 'LIKE', '%' . $num_noches . '%')
                    ->where($afiliadoBool, '>', ($precio_min != "") ? (float)$precio_min : 0)
                    ->where($afiliadoBool, '<', ($precio_max != "") ? (float)$precio_max : 999999999)
                    //->orWhere('nombre_paquete', 'LIKE' , '%' . $caracteristica . '%')
                    ->whereHas(
                        'incluye',
                        function ($query) use ($caracteristica) {
                            $query->where('lugar', 'LIKE', '%' . $caracteristica . '%')
                                ->orWhere('descripcion', 'LIKE', '%' . $caracteristica . '%');
                            $sql = $query->toSql();
                        }
                    )
                    ->latest()->paginate(5),
                "num_dias" => $num_dias,
                "num_noches" => $num_noches,
                "precio_min" => $precio_min,
                "precio_max" => $precio_max,
                "socios" => $request->socios,
                "caracteristica" => $caracteristica
            ]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'message' => ['required', 'min:3', 'max:255'],
                'nombre_paquete' => ['required', 'min:5', 'max:255'],
                'num_dias' => ['required', 'integer', 'min:1'],
                'num_noches' => ['required', 'integer', 'min:1'],
                'precio_afiliado' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
                'precio_no_afiliado' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
                'imagen_paquete' => ['required', 'max:255'],
            ]);

            $imageNames = [];

            if ($request->hasFile('imagen_paquete')) {
                foreach ($request->file('imagen_paquete') as $file) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    $folderName = $request->input('nombre_paquete'); // Obtener el nombre del paquete desde el formulario
                    $file->move('uploads/paquetes/' . $folderName, $filename); // Mover la imagen a la carpeta del paquete
                    $imageNames[] = $folderName . '/' . $filename; // Guardar la ruta completa de la imagen
                }
            }

            $validated['imagen_paquete'] = implode(',', $imageNames);
            $paquete = $request->user()->paquetes()->create($validated);
            $listaCaracteristicas = json_decode($request->get('lista_caracteristicas'));
            foreach ($listaCaracteristicas as $caracteristica) {
                CaracteristicaPaquete::create([
                    'paquete_id' => $paquete->id,
                    'descripcion' => $caracteristica[0],
                    'lugar' => $caracteristica[1],
                ]);
            }


            // Crear un registro en la tabla UserAction
            UserAction::create([
                'user_id' => $request->user()->id,
                'action' => 'crear', // Acción de creación
                'entity_type' => 'paquete', // Tipo de entidad
                'entity_id' => $paquete->id, // ID del paquete creado
            ]);

            return to_route('paquetes.paquetes')
                ->with('status',  __('Insertion done successfully'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function edit(Paquete $paquete)
    {
        $listaJson = json_encode($paquete->incluye);
        return view('paquetes.edit', ['paquete' => $paquete, 'listaJson' => $listaJson]);
    }

    public function update(Request $request, Paquete $paquete)
    {
        $listaModificada = $request->get("lista_caracteristicas_mod");
        $validated = $request->validate([
            'message' => ['required', 'min:3', 'max:255'],
            'nombre_paquete' => ['required', 'min:5', 'max:255'],
            'num_dias' => ['required', 'integer', 'min:1'],
            'num_noches' => ['required', 'integer', 'min:1'],
            'precio_afiliado' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'precio_no_afiliado' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
        ]);


        if ($request->hasFile('imagen_paquete')) {
            $folderName = $request->input('nombre_paquete');
            $folderPath = 'uploads/paquetes/' . $folderName;

            // Eliminar todos los archivos existentes en la carpeta
            File::cleanDirectory($folderPath);

            $imageNames = []; // Para almacenar los nombres de los archivos subidos

            // Iterar sobre cada archivo cargado
            foreach ($request->file('imagen_paquete') as $file) {
                if ($file->isValid()) {
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' . $extension;
                    // Mover el archivo a la carpeta específica
                    $file->move($folderPath, $filename);
                    // Guardar el nombre del archivo en la lista de nombres de archivos
                    $imageNames[] = $folderName . '/' . $filename;
                }
            }
            // Verificar si se han cargado archivos antes de asignarlos al arreglo de datos validados
            if (!empty($imageNames)) {
                // Asignar la lista de nombres de archivos al arreglo de datos validados
                $validated['imagen_paquete'] = implode(',', $imageNames);
            } else {
                // Manejo de errores cuando no se cargan imágenes válidas
            }
        } else {
            // Manejo de errores cuando no haya imágenes
        }

        if ($listaModificada != "") {
            $listaCaracteristicas = json_decode($request->get('lista_caracteristicas_mod'));
            foreach ($listaCaracteristicas as $caracteristica) {
                $tempCar = CaracteristicaPaquete::find($caracteristica->id);
                $tempCar->descripcion = $caracteristica->descripcion;
                $tempCar->lugar = $caracteristica->lugar;
                $tempCar->save();
            }
        } else {
        }
        
        $listaCaracteristicas = json_decode($request->get('lista_caracteristicas'), true);
        if (is_array($listaCaracteristicas) && !empty($listaCaracteristicas)) {
            foreach ($listaCaracteristicas as $caracteristica) {
                // Verificar si la característica ya existe en la base de datos
                $existingCaracteristica = CaracteristicaPaquete::where('descripcion', $caracteristica['descripcion'])
                                                                ->where('lugar', $caracteristica['lugar'])
                                                                ->where('paquete_id', $paquete->id)
                                                                ->first();
                if (!$existingCaracteristica) {
                    // Si no existe, crearla
                    CaracteristicaPaquete::create([
                        'paquete_id' => $paquete->id,
                        'descripcion' => $caracteristica['descripcion'],
                        'lugar' => $caracteristica['lugar'],
                    ]);
                }
            }
        }

        // Crear un registro en la tabla UserAction solo si hay datos modificados
        $originalData = $paquete->getOriginal();
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
                'entity_type' => 'paquete',
                'entity_id' => $paquete->id,
                'modified_data' => json_encode($modifiedData),
            ]);
        }

        $paquete->update($validated);
        return to_route('paquetes.paquetes')
            ->with('status', __('Package updated successfully'));
    }

   

    public function destroy(Paquete $paquete)
    {
        // Guardar los datos del paquete antes de eliminarlo
        $paqueteEliminado = $paquete->toArray();

        // Eliminar la carpeta que contiene las imágenes del paquete
        $folderName = 'uploads/paquetes/' . $paquete->nombre_paquete;
        if (File::exists($folderName)) {
            File::deleteDirectory($folderName);
        }

        // Crear un registro en la tabla UserAction antes de eliminar el paquete
        UserAction::create([
            'user_id' => auth()->id(),
            'action' => 'eliminar',
            'entity_type' => 'paquete',
            'entity_id' => $paquete->id,
            'modified_data' => json_encode($paqueteEliminado),
        ]);

        // Eliminar el paquete
        $paquete->delete();


        return redirect()->route('paquetes.paquetes')
            ->with('status', __('Package deleted successfully'));
    }
}
