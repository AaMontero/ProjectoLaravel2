<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use App\Models\PagoVendedor;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class VendedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()

    {
        return view('vendedor.index', [
            "vendedores" => Vendedor::with('pagosVendedor')->latest()->paginate(10),
            "roles" => [
                'Vendedor', 'Closer', 'Jefe de Sala'
            ],
            "porcentajes" => ['4% Fijo', 'Variable1', 'Variable2'],
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombres' => ['required', 'min:5', 'max:255'],
                'rol' => ['required', 'min:5', 'max:255'],
                'porcentaje_ventas' => ['required', 'min:5', 'max:255']
            ]);

            $request->user()->vendedores()->create($validated);
            return to_route('vendedor.index')
                ->with('status', __('Vendedor creado exitosamente'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendedor $vendedor)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendedor $vendedor)
    {
        return view('vendedor.editar', [
            'vendedor' => $vendedor,
            "roles" => [
                'Vendedor', 'Closer', 'Jefe de Sala'
            ],
            "porcentajes" => ['4% Fijo', 'Variable1', 'Variable2'],
            "estados" => ['Activo', 'Inactivo']
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
                "porcentaje_ventas" => ['required', 'min:5', 'max:255'],
                "activo" => ['required']
            ]);
            $vendedor->activo = $request->activo; 
            $vendedor->update($validated);
            return to_route('vendedor.index')
                ->with('status', __('Actualizado   exitosamente'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendedor $vendedor)
    {
        //
    }

    public function pagosPendientes()
    {
        return view('vendedor.pagos_pendientes', [
            'pagosPendientes' => PagoVendedor::where('estado', "Pendiente")
                ->orderBy('fecha_pago', 'desc')
                ->get(),
            'pagosEfectivos' => PagoVendedor::where('estado', "Pago")
                ->orderBy('fecha_pago', 'desc')
                ->get()
        ]);
    }
}
