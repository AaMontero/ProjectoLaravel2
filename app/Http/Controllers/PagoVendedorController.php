<?php

namespace App\Http\Controllers;

use App\Models\PagoVendedor;
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

    /**
     * Store a newly created resource in storage.
     */
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PagoVendedor $pagoVendedor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PagoVendedor $pagoVendedor)
    {
        //
    }

    public function obtenerValorPago($tipoPago){
        //Aqui va la lógica para obtener el pago
        return 150.50; 
    }
}
