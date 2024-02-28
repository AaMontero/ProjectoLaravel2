<?php

namespace App\Http\Controllers;

use App\Models\Paquete;
use App\Models\CaracteristicaPaquete;
use Illuminate\Http\Request;

class PaqueteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'message' => ['required', 'min:3', 'max:255'],
            'nombre_paquete' => ['required', 'min:5', 'max:255'],
            'num_dias' => ['required', 'integer', 'min:1'],
            'num_noches' => ['required', 'integer', 'min:1'],
            'precio_afiliado' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'precio_no_afiliado' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'imagen_paquete' => ['required', 'min:3', 'max:255'],
        ]);

        if ($request->hasFile('imagen_paquete')) {
            $file = $request->file('imagen_paquete');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;
            $file->move('uploads/paquetes/', $filename);
            $validated['imagen_paquete'] = $filename;
        }

        $paquete = $request->user()->paquetes()->create($validated);
        $listaCaracteristicas = json_decode($request->get('lista_caracteristicas'));
        foreach ($listaCaracteristicas as $caracteristica) {
            CaracteristicaPaquete::create([
                'paquete_id' => $paquete->id,
                'descripcion' => $caracteristica[0],
                'lugar' => $caracteristica[1],
            ]);
        }
        return to_route('paquetes.paquetes')
            ->with('status',  __('Insertion done successfully'));
    }

    public function show(Paquete $paquete)
    {
        //
    }

    public function edit(Paquete $paquete)
    {
        // Convertir la propiedad lista_caracteristicas a una cadena JSON
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
            $file = $request->file('imagen_paquete');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . "." . $extension;
            $file->move('uploads/paquetes/', $filename);
            $validated['imagen_paquete'] = $filename;
        } else {
            //Manejo de errores cuando no haya una imagen
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

        $paquete->update($validated);
        return to_route('paquetes.paquetes')
            ->with('status', __('Package updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paquete $paquete)
    {
        $paquete->delete();
        return to_route('paquetes.paquetes')
            ->with('status', __('Package deleted successfully'));
    }
}
