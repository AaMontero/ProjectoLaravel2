<?php

namespace App\Http\Controllers;

use App\Models\Vendedor;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    
    {
        return view('vendedor.index', [
            "vendedores" => Vendedor::with('contratos')->get(), 
            "roles" => [
                'Vendedor', 'Closer', 'Jefe de Sala'
            ],
            "porcentajes" => ['4% Fijo', 'Variable1', 'Variable2'],
        ]);
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
        file_put_contents("archivoVendedor.txt", $request->nombres); 
        $validated = $request->validate([
            
            'nombres' => ['required', 'min:5', 'max:255'],
            'rol' => ['required', 'min:5', 'max:255'],
            'porcentaje_ventas' => ['required', 'min:5', 'max:255']
        ]);
        $request->user()->vendedores()->create($validated);
        return redirect()->route('vendedor.index')
            ->with('status', __('Inserción realizada exitosamente')); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendedor $vendedor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendedor $vendedor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendedor $vendedor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendedor $vendedor)
    {
        //
    }
}
