<?php

namespace App\Http\Controllers;

use App\Models\PagoVendedor;
use Illuminate\Http\Request;

class PagoVendedorController extends Controller
{
    public function edit(PagoVendedor $pagoVendedor)
    {
        return view(
            'pagoVendedor.editar',
            [
                "pago" => $pagoVendedor,
                "estados" => ["Pendiente", "Pago", "Cancelado"]
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

    public function obtenerValorPago($tipoVendedor, $montoVenta)
    {
        $porcentaje = 0.19;
        $gastoAdministrativo = 150;
        $porcentajeCloser = 0.06;
        $porcentajeLiner = 0.04;
        $porcentajeJefe = 0.02;
        if ($montoVenta >= 2900) {
            $gastoAdministrativo = 250;
            $porcentajeCloser = 0.065;
            $porcentajeLiner = 0.045;
        }
        $valorTotal = $montoVenta - ($porcentaje * $montoVenta) - $gastoAdministrativo;

        if ($tipoVendedor == "Closer") {
            return $valorTotal * $porcentajeCloser;
        }
        if ($tipoVendedor == "Vendedor") {
            return $valorTotal * $porcentajeLiner;
        }
        if ($tipoVendedor == "Jefe de Sala") {
            return $valorTotal * $porcentajeJefe;
        }
        if ($tipoVendedor == "Closer2") {
            return ($valorTotal * $porcentajeCloser) / 2;
        }
    }
    public function pagarVendedor($idVendedor)
    {
        PagoVendedor::where('vendedor_id', $idVendedor)->update(['estado' => 'pago']);
        return back()->with('status', __('Pagos registrados'));
    }
}
