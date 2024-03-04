<?php

namespace App\Http\Controllers;

use App\Models\PagoVendedor;
use DateTime;
use Illuminate\Http\Request;

class PagoVendedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PagoVendedor $pagoVendedor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PagoVendedor $pagoVendedor)
    {
        return view(
            'pagoVendedor.editar',
            [
                "pago" => $pagoVendedor,
                "estados" => ["Pendiente", "Pago"]
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PagoVendedor $pago)
    {

        $validated = $request->validate([
            'valor_pago' => ['required', 'numeric', 'min:0.01', 'max:99999.99'],
            'fecha_pago' => ['required', 'date', 'after_or_equal:today'],
            'concepto' => ['required', 'min:5', 'max:255'],
            'estado' => ['required']
        ]);
        $pago->update($validated);
        return redirect()->route('vendedores.pagosPendientes')
            ->with('status', __('Pago registrado correctamente'));
    }
    public function pagado(PagoVendedor $pago)
    {
        $pago->estado = "Pago";
        $pago->update();
        return redirect()->route('vendedores.pagosPendientes')
            ->with('status', __('Pago realizado'));
    }

    public function quitarPagado(PagoVendedor $pago)
    {
        $pago->estado = "Pendiente";
        $pago->update();
        return redirect()->route('vendedores.pagosPendientes')
            ->with('status', __('Pago realizado'));
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PagoVendedor $pagoVendedor)
    {
        //
    }

    public function obtenerValorPago($tipoPago)
    {
        //Aqui va la lógica para obtener el pago
        return 150.50;
    }
}
