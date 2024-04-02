<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use App\Models\PagoVendedor;
use App\Models\UserAction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VendedorController extends Controller
{
    private $roles = ['Vendedor', 'Closer', 'Jefe de Sala '];
    private $estados = ['Activo', 'Inactivo'];

    public function index()
    {
        $vendedoresIDS = DB::table('model_has_roles')  // ID usuarios vendedores
            ->where('role_id', 3) // 3 es el numero que corresponde a vendedor 
            ->pluck('model_id')
            ->toArray();
        $userVendIds = Vendedor::whereIn('user_vend_id', $vendedoresIDS) //ID usuario sin vendedor asociado
            ->pluck('user_vend_id')
            ->toArray();
        $indicesDiferentes = array_diff($vendedoresIDS, $userVendIds);
        return view('vendedor.index', [
            "usuarios" => User::whereIn('id', $indicesDiferentes)->get(),
            "vendedores" => Vendedor::orderBy("activo", "desc")->get(),
            "roles" => $this->roles,
        ]);
    }


    public function datosVendedor($vendedorId)
    {
        $vendedor = Vendedor::find($vendedorId);
        $listaPagos = PagoVendedor::where('vendedor_id', $vendedor->id)
            ->orderBy('fecha_pago', 'desc')
            ->get();

        $listaMeses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
            'Octubre', 'Noviembre', 'Diciembre'
        ];
        $pagosAgrupados = $listaPagos->groupBy(function ($date) {
            return Carbon::parse($date->fecha_pago)->format('Y-m'); // Agrupa por año y mes
        });

        $sumaPendientes = $listaPagos->where('estado', 'Pendiente')->sum('valor_pago');
        return view(
            'vendedor.detalles',
            [
                'vendedor' => $vendedor,
                'pagosVendedor' => $listaPagos,
                'pagosPendientes' => $sumaPendientes,
                'pagosXmeses' => $pagosAgrupados,
                'mesesanio' => $listaMeses,
            ]
        );
    }

    public function datosVendedorV($vendedorId)
    {
        $vendedor = Vendedor::where('user_vend_id', $vendedorId)->first();
        $listaPagos = PagoVendedor::where('vendedor_id', $vendedor->id)
            ->orderBy('fecha_pago', 'desc')
            ->get();

        $listaMeses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
            'Octubre', 'Noviembre', 'Diciembre'
        ];
        $pagosAgrupados = $listaPagos->groupBy(function ($date) {
            return Carbon::parse($date->fecha_pago)->format('Y-m'); // Agrupa por año y mes
        });

        $sumaPendientes = $listaPagos->where('estado', 'Pendiente')->sum('valor_pago');
        return view(
            'vendedor.detalles',
            [
                'vendedor' => $vendedor,
                'pagosVendedor' => $listaPagos,
                'pagosPendientes' => $sumaPendientes,
                'pagosXmeses' => $pagosAgrupados,
                'mesesanio' => $listaMeses,
            ]
        );
    }

    public function store(Request $request)
    {
        try {

            $validated = $request->validate([
                'nombres' => ['required', 'min:5', 'max:255'],
                'rol' => ['required', 'min:5', 'max:255'],
                'user_vend_id' => ['required'],
            ]);

            // Crear el vendedor
            $vendedor = $request->user()->vendedores()->create($validated);

            // Crear un registro en la tabla de UserAction
            UserAction::create([
                'user_id' => $request->user()->id,
                'action' => 'crear', // Acción de inserción
                'entity_type' => 'vendedor', // Tipo de entidad
                'entity_id' => $vendedor->id, // ID del vendedor creado

            ]);
            return to_route('vendedor.index')
                ->with('status', __('Vendedor creado exitosamente'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function edit(Vendedor $vendedor)
    {
        return view('vendedor.editar', [
            'vendedor' => $vendedor,
            "roles" => $this->roles,
            "estados" => $this->estados,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Vendedor $vendedor)
    {
        try {
            $validated = $request->validate([
                "nombres" => ['required', 'min:5', 'max:255'],
                "rol" => ['required', 'min:5', 'max:255'],
                "activo" => ['required']
            ]);
            // Obtener el vendedor antes de la actualización
            $vendedorAnterior = $vendedor->getAttributes();
            // Actualizar el vendedor con los datos validados
            $vendedor->update($validated);
            // Obtener el vendedor después de la actualización
            $vendedorActualizado = $vendedor->refresh();
            // Comparar los datos antes y después de la actualización para detectar cambios
            $modifiedData = array_diff_assoc($vendedorActualizado->getAttributes(), $vendedorAnterior);
            // Crear un registro en UserAction si hay datos modificados
            if (!empty($modifiedData)) {
                UserAction::create([
                    'user_id' => $request->user()->id,
                    'action' => 'actualizar',
                    'entity_type' => 'vendedor',
                    'entity_id' => $vendedor->id,
                    'modified_data' => json_encode($modifiedData),
                    // Otros campos relevantes
                ]);
            }

            // Redireccionar a la vista de índice de vendedores con un mensaje de éxito
            return redirect()->route('vendedor.index')
                ->with('status', __('Actualizado exitosamente'));
        } catch (ValidationException $e) {
            // Manejar errores de validación devolviendo a la vista anterior con errores
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }


    public function pagosPendientes()
    {
        return view('vendedor.pagos_pendientes', [
            'pagosEfectivos' => PagoVendedor::where('estado', "Pago")
                ->orderBy('updated_at', 'desc')
                ->paginate(15),
            'vendedores' => Vendedor::all(),
            'pagosPendientesPorVendedor' => PagoVendedor::where('estado', 'pendiente')->get()->groupBy('vendedor_id'),
            'pagosPendientes' => PagoVendedor::where('estado', "Pendiente")
                ->orderBy('fecha_pago', 'desc')
                ->get()
        ]);
    }

    public function cambiarActivo(Request $request, Vendedor $vendedor)
    {
        if ($vendedor->activo == "1") {
            $vendedor->activo = 0;
            $vendedor->update();
        } elseif ($vendedor->activo == "0") {
            $vendedor->activo = 1;
            $vendedor->update();
        }


        UserAction::create([
            'user_id' => $request->user()->id,
            'action' => 'cambiar estado a inactivo', // Acción de inserción
            'entity_type' => 'vendedor', // Tipo de entidad
            'entity_id' => $vendedor->id, // ID del vendedor creado


        ]);
        return to_route('vendedor.index')
            ->with('status', __('Se ha cambiado el estado del vendedor'));;
    }
}
