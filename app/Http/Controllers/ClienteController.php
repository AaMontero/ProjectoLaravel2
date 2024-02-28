<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $provinciasEcuador = [
            'Azuay', 'Bolívar', 'Cañar', 'Carchi', 'Chimborazo', 'Cotopaxi', 'El Oro', 'Esmeraldas', 'Galápagos',
            'Guayas', 'Imbabura', 'Loja', 'Los Ríos', 'Manabí', 'Morona Santiago', 'Napo', 'Orellana', 'Pastaza', 'Pichincha',
            'Santa Elena', 'Santo Domingo de los Tsáchilas', 'Sucumbíos', 'Tungurahua', 'Zamora-Chinchipe'
        ];

        return view('clientes.index', [
            "clientes" => Cliente::with('user')->get(),
            "provincias" => $provinciasEcuador,
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
        $validated = $request->validate([
            'cedula' => ['required', 'min:8', 'max:20'],
            'nombres' => ['required', 'min:5', 'max:255'],
            'apellidos' => ['required', 'min:5', 'max:255'],
            'numTelefonico' => ['required', 'min:7', 'max:12'],
            'email' => ['required', 'email', 'min:5', 'max:255'],
            'ciudad' => ['required', 'min:5', 'max:255'],
            'provincia' => ['required', 'min:5', 'max:255'],
            'activo' => ['nullable', 'boolean', 'in:0,1', 'default' => 1],
            'fecha_nacimiento' => ['required', 'date', 'before_or_equal:' . 
            now()->subYears(18)->format('Y-m-d')],

            
        ]);
        $clienteUser = $this->obtenerNick($request->nombres, $request->apellidos);
        $validated['cliente_user'] = $clienteUser;
        $request->user()->clientes()->create($validated);
        return redirect()->route('clientes.index')
            ->with('status', __('Inserción realizada exitosamente'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        $provinciasEcuador = [
            'Azuay', 'Bolívar', 'Cañar', 'Carchi', 'Chimborazo', 'Cotopaxi', 'El Oro', 'Esmeraldas', 'Galápagos',
            'Guayas', 'Imbabura', 'Loja', 'Los Ríos', 'Manabí', 'Morona Santiago', 'Napo', 'Orellana', 'Pastaza', 'Pichincha',
            'Santa Elena', 'Santo Domingo de los Tsáchilas', 'Sucumbíos', 'Tungurahua', 'Zamora-Chinchipe'
        ];
        return view('clientes.edit', [
            'cliente' => $cliente,
            'provincias' => $provinciasEcuador
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {

        $validated = $request->validate([
            'cedula' => ['required', 'min:8', 'max:20'],
            'nombres' => ['required', 'min:5', 'max:255'],
            'apellidos' => ['required', 'min:5', 'max:255'],
            'numTelefonico' => ['required', 'min:7', 'max:12'],
            'email' => ['required', 'email', 'min:5', 'max:255'],
            'ciudad' => ['required', 'min:5', 'max:255'],
            'provincia' => ['required', 'min:5', 'max:255'],
            'activo' => ['nullable', 'boolean', 'in:0,1', 'default' => 1],
        ]);
        //$request->user()->clientes()->update($validated);
        $cliente->update($validated);
        return to_route('clientes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        //
    }

    public function obtenerNick($nombres, $apellidos)
    {
        $apellidosArray = explode(' ', $apellidos);
        $primerApellido = $apellidosArray[0];
        $segundoApellido = count($apellidosArray) > 1 ? substr($apellidosArray[1], 0, 1) : '';
        $primeraLetraNombre = substr($nombres, 0, 1);
        $usuario = strtolower($primeraLetraNombre . $primerApellido . $segundoApellido);
        return $usuario;
    }
}
