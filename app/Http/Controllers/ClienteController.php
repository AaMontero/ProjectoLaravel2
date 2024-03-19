<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\UserAction;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ClienteController extends Controller
{
    private $provinciasEcuador = [
        'Azuay', 'Bolívar', 'Cañar', 'Carchi', 'Chimborazo', 'Cotopaxi', 'El Oro', 'Esmeraldas', 'Galápagos',
        'Guayas', 'Imbabura', 'Loja', 'Los Ríos', 'Manabí', 'Morona Santiago', 'Napo', 'Orellana', 'Pastaza', 'Pichincha',
        'Santa Elena', 'Santo Domingo de los Tsáchilas', 'Sucumbíos', 'Tungurahua', 'Zamora-Chinchipe'
    ];

    public function obtenerDetallesCliente($clienteId)
    {
        // Obtener detalles del cliente desde la base de datos u otra fuente
        $cliente = Cliente::find($clienteId);
        return view('contratos.contrato', ['cliente' => $cliente]);
    }


    public function index(Request $request)
    {
        return view('clientes.index', [
            "clientes" => Cliente::all(),
            "provincias" => $this->provinciasEcuador,
        ]);
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

            //Validación si ya existe un cliente con el mismo email o número de cédula 
            $existingCliente = Cliente::where('email', $validated['email'])
                ->orWhere('cedula', $validated['cedula'])
                ->first();

            if ($existingCliente) {
                return redirect()->back()
                    ->withErrors(['email' => 'Este correo electrónico ya está registrado.', 'cedula' => 'Este número de cédula ya está registrado.'])
                    ->withInput();
            }

            $clienteUser = $this->obtenerNick($request->nombres, $request->apellidos);
            $validated['cliente_user'] = $clienteUser;
            $cliente = $request->user()->clientes()->create($validated);

            // Crear un registro en la tabla de UserAction
            UserAction::create([
                'user_id' => $request->user()->id,
                'action' => 'crear',
                'entity_type' => 'cliente',
                'entity_id' => $cliente->id,
            ]);


            return redirect()->route('clientes.index')->with('status', __('Inserción realizada exitosamente'));
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }

    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', [
            'cliente' => $cliente,
            'provincias' => $this->provinciasEcuador
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

        $existingCliente = $this->validarExisteCedula($cliente, $request->email);
        if ($existingCliente) {
            return redirect()->back()->withErrors(['email' => 'Este correo electrónico ya está registrado.'])->withInput();
        }



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
    public function validarExisteCedula($cliente, $email)
    {
        Cliente::where('email', $email)
            ->where('id', '!=', $cliente->id)
            ->first();
    }
}
