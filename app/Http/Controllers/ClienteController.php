<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\UserAction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClienteController extends Controller
{

    /**
     * Display a listing of the resource.
     */


    public function obtenerDetallesCliente($clienteId)
    {
        // Obtener detalles del cliente desde la base de datos u otra fuente
        $cliente = Cliente::find($clienteId);
        return view('contratos.contrato', ['cliente' => $cliente]);
    }


    public function index(Request $request)
    {
        $provinciasEcuador = [
            'Azuay', 'Bolívar', 'Cañar', 'Carchi', 'Chimborazo', 'Cotopaxi', 'El Oro', 'Esmeraldas', 'Galápagos',
            'Guayas', 'Imbabura', 'Loja', 'Los Ríos', 'Manabí', 'Morona Santiago', 'Napo', 'Orellana', 'Pastaza', 'Pichincha',
            'Santa Elena', 'Santo Domingo de los Tsáchilas', 'Sucumbíos', 'Tungurahua', 'Zamora-Chinchipe'
        ];

        return view('clientes.index', [
            "clientes" => Cliente::all(),
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
        try {
            $validated = $request->validate([
                'cedula' => ['required', 'min:10', 'max:10'],
                'nombres' => ['required', 'min:5', 'max:255'],
                'apellidos' => ['required', 'min:5', 'max:255'],
                'numTelefonico' => ['required', 'min:7', 'max:12'],
                'email' => ['required', 'email', 'min:5', 'max:255'],
                'ciudad' => ['required', 'min:5', 'max:255'],
                'provincia' => ['required', 'min:5', 'max:255'],
                'activo' => ['nullable', 'boolean', 'in:0,1', 'default' => 1],
                'fecha_nacimiento' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            ]);
            $clienteUser = $this->obtenerNick($request->nombres, $request->apellidos);
            $validated['cliente_user'] = $clienteUser;
            
            // Crear un cliente
            $cliente = $request->user()->clientes()->create($validated);
            
            // Crear un registro en la tabla de UserAction
            UserAction::create([
                'user_id' => $request->user()->id,
                'action' => 'crear',
                'entity_type' => 'cliente',
                'entity_id' => $cliente->id,
                // Otros campos relevantes que desees registrar en el log
            ]);
        

            return redirect()->route('clientes.index')->with('status', __('Inserción realizada exitosamente'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
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
            'cedula' => ['required', 'min:10', 'max:10'],
            'nombres' => ['required', 'min:5', 'max:255'],
            'apellidos' => ['required', 'min:5', 'max:255'],
            'numTelefonico' => ['required', 'min:7', 'max:12'],
            'email' => ['required', 'email', 'min:5', 'max:255'],
            'ciudad' => ['required', 'min:5', 'max:255'],
            'provincia' => ['required', 'min:5', 'max:255'],
            'activo' => ['nullable', 'boolean', 'in:0,1', 'default' => 1],
            'fecha_nacimiento' => ['required', 'date'],
        ]);
    
        // Guardar los datos originales del cliente antes de la actualización
        $originalData = $cliente->getAttributes();
    
        // Actualizar el cliente
        $cliente->update($validated);
    
        // Obtener los datos modificados del cliente
        $modifiedData = array_diff_assoc($cliente->getAttributes(), $originalData);
    
        // Convertir los datos modificados a JSON
        $modifiedDataJson = json_encode($modifiedData);
    
        // Crear un registro en la tabla UserAction
        UserAction::create([
            'user_id' => $request->user()->id,
            'action' => 'actualizar',
            'entity_type' => 'cliente',
            'entity_id' => $cliente->id,
            'modified_data' => $modifiedDataJson,
        ]);
    
        return redirect()->route('clientes.index')
            ->with('status', __('Actualización realizada exitosamente'));
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
