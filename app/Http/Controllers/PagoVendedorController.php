<?php

namespace App\Http\Controllers;

use App\Models\PagoVendedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PagoVendedorController extends Controller
{
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
    public function update(Request $request, PagoVendedor $pago)
    {

        $validated = $request->validate([
            'valor_pago' => ['required', 'numeric', 'min:0.01', 'max:99999.99'],
            'fecha_pago' => ['required', 'date'],
            'concepto' => ['required', 'min:5', 'max:255'],
            'estado' => ['required'],
            'closer1' => ['required', Rule::unique('tu_tabla')->ignore($pago->id)],
            'closer2' => ['required', Rule::unique('tu_tabla')->ignore($pago->id)],
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

    public function obtenerValorPago($tipoPago)
    {
        //Aqui va la lógica para obtener el pago
        return 150.50;
    }
    public function pagarVendedor($idVendedor)
    {
        PagoVendedor::where('vendedor_id', $idVendedor)->update(['estado' => 'pago']);
        return back()->with('status', __('Pagos registrados'));
    }
}
