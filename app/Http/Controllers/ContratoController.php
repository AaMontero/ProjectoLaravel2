<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Vendedor;
use App\Models\PagoVendedor;
use Exception;
use App\Models\Contrato;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use DateTime;
use DateInterval;
use NumberFormatter;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PagoVendedorController;
use App\Models\UserAction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

$meses = array(
    1 => 'Enero',
    2 => 'Febrero',
    3 => 'Marzo',
    4 => 'Abril',
    5 => 'Mayo',
    6 => 'Junio',
    7 => 'Julio',
    8 => 'Agosto',
    9 => 'Septiembre',
    10 => 'Octubre',
    11 => 'Noviembre',
    12 => 'Diciembre'
);


$cliente_constructor;
class ContratoController extends Controller
{
    public function index()
    {

        $clientes = Cliente::all();

        return view('contratos.contrato', [
            "contratos" => Contrato::with('Cliente')->orderBy('created_at', 'desc')->get(),
            "clientes" => $clientes,
            "vendedores" => Vendedor::all(),
        ]);
    }

    // Obtener los datos del cliente para el modal
    public function obtenerDetallesCliente($id)
    {
        $cliente = Cliente::find($id);
        return response()->json($cliente);
    }
    public function eliminarContrato($contrato)
    {
        //FALTA POR IMPLEMENTAR
    }


    public function add_contrato(Cliente $cliente)
    {
        return view('contratos.addNew', [
            "cliente" => $cliente
        ]);
    }
    public function add_vendedor($contratoId)
    {
        $contrato = Contrato::find($contratoId);
        $montoContrato = $contrato->monto_contrato;
        $idCliente = $contrato->cliente_id;
        $clienteActivo = Cliente::find($idCliente);
        $rutaBase =  'contratos'; // Ruta Servidor Contratos 
        // $rutaBase = "../public/contratos";
        $nombreCarpeta = $clienteActivo->nombres . " " . $clienteActivo->apellidos . " " . date("Y-m-d") . ".zip";
        $rutaCarpeta = $rutaBase . '/' . $nombreCarpeta;
        return view('contratos.contrato_vendedores', [
            "montoContrato" => $montoContrato,
            "ruta" => $rutaCarpeta,
            "contratoId" => $contratoId,
            "vendedores" => Vendedor::where('rol', "Vendedor")->where('activo', 1)->get(),
            "closers" => Vendedor::where('rol', "Closer")->where('activo', 1)->get(),
            "jefes_sala" => Vendedor::where('rol', "Jefe de Sala")->where('activo', 1)->get(),
        ]);
    }
    public function add_vendedores_DB(Request $request)
    {

        $request->validate([
            'vendedor' => 'required',
            'closer1' => 'required',
            'jefe_de_sala' => 'required',
        ]);
        if (!empty($errores)) {
            return redirect()->back()->withErrors($errores)->withInput();
        }
        if ($request->closer1 == $request->closer2) {
            return redirect()->back()->withErrors(['duplicado' => __('Los closers no pueden ser los mismos.')])->withInput();
        }
        $valorPagado = $request->monto_pagado;
        $contratoId = $request->contratoId;
        $contrato = Contrato::find($contratoId);
        $contrato->vendedor_id = $request->vendedor;
        $contrato->closer_id = $request->closer1;
        $contrato->closer2_id = $request->closer2;
        $contrato->jefe_sala_id = $request->jefe_de_sala;
        $contrato->save();
        $vendedor = Vendedor::find($request->vendedor);
        $closer1 = Vendedor::find($request->closer1);
        $closer2 = Vendedor::find($request->closer2);
        $jefeDeSala = Vendedor::find($request->jefe_de_sala);
        $controlerPV = new PagoVendedorController();
        $utils = new Utils();
        $utils->agregarPago($vendedor, $contrato, $valorPagado, $controlerPV);
        $utils->agregarPago($closer1, $contrato, $valorPagado, $controlerPV);
        if (isset($closer2)) {
            $utils->agregarPago($closer2, $contrato, $valorPagado, $controlerPV);
        }

        $utils->agregarPago($jefeDeSala, $contrato, $valorPagado, $controlerPV);

        return to_route('contrato.index')
            ->with('status', __('Contrato creado exitosamente'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $utils = new Utils();
        date_default_timezone_set('America/Guayaquil');
        $formasPago = $request->input('formas_pago'); //Lista Formas de Pago
        //Inicializacion de variables
        $nombres = $email = $apellidos = $ciudad = $provincia = $ubicacionSala = $contratoId = $pagareText = $montoCuotaPagare = "";
        $lugarInternacional = "";
        $aniosContrato = $montoContrato = $personasInternacional = 0;
        $bonoQory = $bonoQoryInt = $contienePagare = $contieneCreditoDirecto = $vacacionalIntBool = $semanaIntBoolean = false;
        $fechaActual = $fechaVencimiento = $fechaInicioCredDir = date("Y-m-d");
        try {
            $tieneUsuario = $request->usuario_previo;
            if (empty($tieneUsuario)) {
                $request->validate([
                    'cedula' => ['required', 'min:10', 'max:10'],
                    'nombres' => ['required', 'min:5', 'max:255'],
                    'apellidos' => ['required', 'min:5', 'max:255'],
                    'email' => ['required', 'email', 'min:5', 'max:255'],
                    'ciudad' => ['required', 'min:4', 'max:255'],
                    'provincia' => ['required', 'min:5', 'max:255'],
                    'ubicacion_sala' => ['required', 'min:5', 'max:255'],
                    'anios_contrato' => ['required', 'integer', 'min:0'],
                    'monto_contrato' => ['required', 'numeric', 'min: 0.01'],
                ]);
            } else {
                $request->validate([
                    'ubicacion_sala' => ['required', 'min:5', 'max:255'],
                    'anios_contrato' => ['required', 'integer', 'min:0'],
                    'monto_contrato' => ['required', 'numeric', 'min: 0.01'],
                ]);
            }


            $nombres = $request->nombres;
            $email = $request->email;
            $apellidos = $request->apellidos;
            $ciudad = $request->ciudad;
            $numCedula = $request->cedula;
            $provincia = $request->provincia;
            $ubicacionSala = $request->ubicacion_sala;
            $aniosContrato = $request->anios_contrato;
            $montoContrato = $request->monto_contrato;
            $contienePagare = $request->contiene_pagare;
            $contieneCreditoDirecto = $request->contiene_credito_directo;
            $okBono = isset($request->bono_hospedaje);
            $okBonoInt = isset($request->bono_hospedaje_internacional);
            $bonoVacacionalInt = isset($request->bono_certificado_vacacional_internacional);
            $bonoSemanaInt = isset($request->bono_semana_internacional);
            $listaOtros = [];
            $okBono == 1 ? $bonoQory = true : $bonoQory = false;
            $okBonoInt == 1 ? $bonoQoryInt = true : $bonoQoryInt = false;
            $bonoVacacionalInt == 1 ? $vacacionalIntBool = true : $vacacionalIntBool = false;
            $bonoSemanaInt == 1 ? $semanaIntBoolean = true : $semanaIntBoolean = false;
            if ($vacacionalIntBool || $semanaIntBoolean) {
                $request->validate([
                    'personas_bono_semana_internacional' => ['required', 'integer', 'between:2,6'],
                    'lugar_bono_semana_internacional' => ['required', 'not_in:Miami'],
                ], [
                    'personas_bono_semana_internacional.between' => 'El campo numero de personas debe tener un valor entre 2 y 6.',
                    'lugar_bono_semana_internacional.not_in' => 'El campo lugar no puede ser Miami.',
                ]);
                $lugarInternacional = $request->lugar_bono_semana_internacional;
                $personasInternacional = $request->personas_bono_semana_internacional;
            } else {
            }
            $fomasPagoSinComillas = str_replace("[", "", $formasPago);
            $fomasPagoSinComillas2 = str_replace("]", "", $fomasPagoSinComillas);
            $formasPagoLista = explode(",", $fomasPagoSinComillas2);
            foreach ($formasPagoLista as $elem) {
                $elemDividido = explode(" ", $elem);
                if (count($elemDividido) >= 2 && strpos($elem, "Pagaré") === false) {
                    $valNum = $elemDividido[0];
                    $valText = implode(" ", array_slice($elemDividido, 1));
                    $listaOtros[] = [$valNum, $valText];
                }
            }
            $StringOtrosFormaPago = "";
            foreach ($listaOtros as $item) {
                if ($item && strpos($item[1], "con") === 0) {
                    $StringOtrosFormaPago .= "[" . $item[0] . " , " . str_replace("con", "", $item[1]) . "]";
                }
            }
            str_replace('"', '', $StringOtrosFormaPago);
            // Validación de datos
            $ciudad_diccionario = [
                "Quito" => "UIO",
                "quito" => "UIO",
                "Guayaquil" => "GYE",
                "guayaquil" => "GYE",
                "santo domingo" => "STO",
                "Santo domingo" => "STO",
                "Santo Domingo" => "STO",
                "Salcedo" => "SLCDO",
                "salcedo" => "SLCDO",
            ];

            if ($user->sala == 'Sala 1') {
                $letrasContrato = "QTA_";
                $numInicial = 40000;
                $numero_sucesivo =  $utils->obtenerNumeroMayorTipo("Sala 1");
            }
            if ($user->sala == "Sala 2") {
                $letrasContrato = "QT_";
                $numInicial = 30000;
                $numero_sucesivo =  $utils->obtenerNumeroMayorTipo("Sala 2");
            }
            if (array_key_exists($ciudad, $ciudad_diccionario)) { // Si la ciudad esta en el diccionario
                $codigo_ciudad = $ciudad_diccionario[$ciudad];
                if ($contieneCreditoDirecto == 1) {
                    $contratoId = "CD_" . $letrasContrato . $codigo_ciudad;
                } else {
                    $contratoId = $letrasContrato . $codigo_ciudad;
                }
            } else {
                $codigo_ciudad = $ciudad;
                if ($contieneCreditoDirecto == 1) {
                    $contratoId = "CD_" . $letrasContrato . $codigo_ciudad;
                } else {
                    $contratoId = $letrasContrato . $codigo_ciudad;
                }
            }
            $nombre_cliente = $nombres . " " . $apellidos;

            $valorPagare = json_decode($request->pagare_monto_info);
            $fechaVencimiento = json_decode($request->pagare_fecha_info);
            $formasPagoString = json_decode($request->formas_pago);
            $fechaInicioCredDir = json_decode($request->cred_dir_fecha_inicio);
            $numCuotasCredDir = json_decode($request->cred_dir_num_cuotas);
            $montoCredDir = json_decode($request->cred_dir_valor);
            $abonoCredDir = json_decode($request->cred_dir_abono);
            $listaDocumentos = [];
            if ($abonoCredDir == "") {
                $abonoCredDir = 0;
            }
            if (empty($formasPagoString)) {
                return redirect()->back()->withErrors(['formas_pago' => 'Inserte una forma de pago'])->withInput();
            } else {
                foreach ($formasPagoString as $forma) {
                    $formasPago = $formasPago . $forma . "\n \n";
                }
                $funciones = new DocumentGenerator();
                $rutaCarpetaSave = $funciones->crearCarpetaCliente($nombre_cliente, $fechaActual);
                $listaDocumentos[] = $funciones->generarVerificacion($nombre_cliente, $numero_sucesivo, $numCedula, $rutaCarpetaSave);
                $listaDocumentos[] = $funciones->generarDiferimiento($contratoId, $numero_sucesivo, $ciudad, $numCedula, $fechaActual, $nombre_cliente, $rutaCarpetaSave);
                if ($contieneCreditoDirecto != true && $contienePagare != true) { // No contiene credito directo ni pagare
                    $listaDocumentos[] = $funciones->generarContrato($contratoId, $nombre_cliente, $numero_sucesivo, $numCedula, $montoContrato, $aniosContrato, $formasPagoString, $email, $fechaActual, $ciudad, $rutaCarpetaSave);
                    $listaDocumentos[] = $funciones->generarBeneficiosAlcance($contratoId, $numero_sucesivo, $nombre_cliente, $numCedula, $bonoQory, $bonoQoryInt, $vacacionalIntBool, $semanaIntBoolean, $rutaCarpetaSave, false, $lugarInternacional, $personasInternacional);
                    $listaDocumentos[] = $funciones->generarCheckList($contratoId, $numero_sucesivo, $ciudad, $provincia,  $numCedula, $email, $fechaActual, $nombre_cliente, $ubicacionSala, $rutaCarpetaSave, "Descuento para pagos con tarjeta");
                }
                if ($contieneCreditoDirecto == true) { // SI contiene credito directo
                    $valorPendiente = ($montoCredDir - $abonoCredDir);
                    $resultado =  $valorPendiente / $numCuotasCredDir;
                    $valorCuota = ceil($resultado * 100) / 100;
                    $valorCuota = number_format($valorCuota, 2);
                    $listaDocumentos[] = $funciones->generarCheckList($contratoId, $numero_sucesivo, $ciudad, $provincia,  $numCedula, $email, $fechaActual, $nombre_cliente, $ubicacionSala, $rutaCarpetaSave, "Débito Automatico");
                    $listaDocumentos[] = $funciones->generarBeneficiosAlcance($contratoId, $numero_sucesivo, $nombre_cliente, $numCedula, $bonoQory, $bonoQoryInt, $vacacionalIntBool, $semanaIntBoolean, $rutaCarpetaSave, true, $lugarInternacional, $personasInternacional);
                    $listaDocumentos[] = $funciones->generarContratoCreditoDirecto($contratoId, $nombre_cliente, $numero_sucesivo, $numCedula, $montoContrato, $aniosContrato, $formasPagoString, $email, $fechaActual, $ciudad, $rutaCarpetaSave, $abonoCredDir, $numCuotasCredDir, $valorCuota);
                    $listaDocumentos[] = $funciones->generarPagaresCredito($fechaInicioCredDir, $montoCredDir, $abonoCredDir, $numCuotasCredDir, $rutaCarpetaSave, $numero_sucesivo, $nombre_cliente, $ciudad, $numCedula, $fechaActual, $email);
                }
                if ($contienePagare == true) { // Si contiene pagare
                    $listaDocumentos[] = $funciones->generarContrato($contratoId, $nombre_cliente, $numero_sucesivo, $numCedula, $montoContrato, $aniosContrato, $formasPagoString, $email, $fechaActual, $ciudad, $rutaCarpetaSave);
                    $listaDocumentos[] = $funciones->generarBeneficiosAlcance($contratoId, $numero_sucesivo, $nombre_cliente, $numCedula, $bonoQory, $bonoQoryInt, $vacacionalIntBool, $semanaIntBoolean, $rutaCarpetaSave, false, $lugarInternacional, $personasInternacional);
                    $listaDocumentos[] = $funciones->generarCheckList($contratoId, $numero_sucesivo, $ciudad, $provincia,  $numCedula, $email, $fechaActual, $nombre_cliente, $ubicacionSala, $rutaCarpetaSave, "Descuento para pagos con tarjeta");
                    $listaDocumentos[] = $funciones->generarPagare($nombre_cliente, $numCedula, $numero_sucesivo, $fechaVencimiento, $ciudad, $email, $valorPagare, $fechaActual, 1, $montoCuotaPagare, $pagareText, $rutaCarpetaSave);
                }
                $utils->comprimirArchivos($rutaCarpetaSave, $listaDocumentos);
            }
            //Creación del cliente
            if (empty($tieneUsuario)) {
                // Si $tieneUsuario está vacío, busca un cliente por cédula
                $clienteExiste = Cliente::where('cedula', $numCedula)->first();
                if (!$clienteExiste) {
                    // Si no existe el cliente, crea uno nuevo asociándolo al usuario actual
                    $controler = new ClienteController();
                    $cliente = new Cliente();
                    $cliente->nombres = $nombres;
                    $cliente->apellidos = $apellidos;
                    $cliente->ciudad = $ciudad;
                    $cliente->cedula = $numCedula;
                    $cliente->provincia = $provincia;
                    $cliente->numTelefonico = "";
                    $cliente->fecha_nacimiento = null;
                    $cliente->email = $email;
                    $cliente->activo = true;
                    $cliente->cliente_user = $controler->obtenerNick($nombres, $apellidos);
                    // Asociar el cliente al usuario actual
                    $cliente->user()->associate(auth()->user());
                    $cliente->save();
                    $persona = $cliente;
                } else {
                    // Si ya existe el cliente, asigna el cliente existente a $persona
                    $persona = $clienteExiste;
                }
            } else {
                // Si $tieneUsuario no está vacío, busca el cliente por el ID proporcionado
                $persona = Cliente::find($tieneUsuario);
            }
            //Creación del contrato
            $contrato = new Contrato();
            if ($contieneCreditoDirecto) {
                $contrato->valor_pagare = ($montoCredDir - $abonoCredDir);
                $fechaFinalCredDir =  new DateTime($fechaInicioCredDir);
                $fechaFinalCredDirV = $fechaFinalCredDir->modify('+' . $numCuotasCredDir . ' month');
                $contrato->fecha_fin_pagare =  $fechaFinalCredDirV->format('Y-m-d');
                $contrato->valor_total_credito_directo = $montoCredDir;
                $contrato->meses_credito_directo = $numCuotasCredDir;
                $contrato->abono_credito_directo = $abonoCredDir;
            } else if ($contienePagare) {
                $contrato->valor_pagare = $valorPagare;
                $contrato->fecha_fin_pagare = $fechaVencimiento;
                $contrato->meses_credito_directo = 1;
            }
            if ($StringOtrosFormaPago != "") { // Se ejecuta cuando hay otra opción de pago
                $contrato->otro_comentario = $StringOtrosFormaPago;
            }
            $contrato->ubicacion_sala = $ubicacionSala;
            $contrato->anios_contrato = $aniosContrato;
            $contrato->monto_contrato = $montoContrato;
            $contrato->bono_hospedaje_qori_loyalty = $bonoQory;
            $contrato->bono_hospedaje_internacional = $bonoQoryInt;
            $contrato->bono_certificado_vacacional_internacional  = $vacacionalIntBool;
            $contrato->bono_semana_internacional = $semanaIntBoolean;
            $contrato->lugar_internacional = $lugarInternacional;
            $contrato->personas_internacional = $personasInternacional;
            $contrato->contrato_id = $contratoId . $numero_sucesivo;
            $personaArray = json_decode($persona, true);
            if (!empty($personaArray) && isset($personaArray['id'])) {
                $contrato->cliente_id = $personaArray['id'];
            } elseif (!empty($personaArray) && isset($personaArray[0]['id'])) {
                $contrato->cliente_id = $personaArray[0]['id'];
            } else {
                $contrato->cliente_id = null;
            }

            $contratoIngresado = $request->user()->contratos()->create($contrato->toArray());

            // Crear un registro en la tabla de registros
            UserAction::create([
                'user_id' => $request->user()->id,
                'action' => 'crear', // Acción de crear contrato
                'entity_type' => 'contrato', // Tipo de entidad
                'entity_id' => $contratoIngresado->id, // ID del contrato creado
                // Otros campos relevantes que desees registrar en el log
            ]);
            return to_route(
                'contrato.vendedores',
                [
                    'contratoId' => $contratoIngresado->id,
                ]
            );

            //return route('contrato.vendedores', ['contrato' => $contrato]);

        } catch (ValidationException $e) {
            $errors = $e->validator->errors();
            $errorString = '';
            foreach ($errors->all() as $error) {
                $errorString .= $error . "\n";
            }
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Contrato $contrato)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contrato $contrato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contrato $contrato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contrato $contrato)
    {
        // Guardar los datos del paquete antes de eliminarlo
        $contratoEliminado = $contrato->toArray();

        // Construir la ruta de la carpeta del contrato
        $nombreCliente = $contrato->cliente->nombres . ' ' . $contrato->cliente->apellidos;
        $fechaCreacion = $contrato->created_at->format('Y-m-d');
        $nombreCarpeta = $nombreCliente . " " . $fechaCreacion;
        $rutaBase = $_SERVER['DOCUMENT_ROOT'] . '/contratos';
        //$rutaBase = '../public/contratos';
        $rutaCarpeta = $rutaBase . '/' . $nombreCarpeta;
        //Creacion de carpeta local
        // $nombreUsuario = getenv("USERNAME");
        // $rutaCarpeta = "C:\\Users\\$nombreUsuario\\Documents\\Contratos\\$nombreCarpeta";
        // Verificar si la carpeta existe antes de intentar eliminarla
        if (is_dir($rutaCarpeta)) {
            // Eliminar todos los archivos dentro del directorio
            foreach (glob($rutaCarpeta . '/*') as $archivo) {
                if (is_file($archivo))
                    unlink($archivo); // Eliminar el archivo
            }

            // Eliminar el directorio vacío
            if (!rmdir($rutaCarpeta)) {
                throw new Exception("Error al eliminar la carpeta: $rutaCarpeta");
            }
        }

        // Crear un registro en la tabla UserAction antes de eliminar el paquete
        UserAction::create([
            'user_id' => auth()->id(),
            'action' => 'eliminar',
            'entity_type' => 'contrato',
            'entity_id' => $contrato->id,
            'modified_data' => json_encode($contratoEliminado),
        ]);


        // Eliminar el paquete
        $contrato->delete();


        return redirect()->route('contrato.index')
            ->with('status', __('Contrato eliminando exitosamente'));
    }
}

class DocumentGenerator
{

    public function crearCarpetaCliente($nombre_cliente, $fechaActual)
    {
        global $meses;
        $meses = array(
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        );
        // Ruta para acceso Local
        // $nombreUsuario = getenv("USERNAME"); //Obtiene el nombre del usuario desde la EV
        // $nombreCarpeta = $nombre_cliente . " " . $fechaActual;
        // $rutaCarpeta = "C:\\Users\\$nombreUsuario\\Documents\\Contratos\\$nombreCarpeta";
        $rutaBase = $_SERVER['DOCUMENT_ROOT'] . '/contratos'; // Ruta Servidor Contratos 
        // $rutaBase = "../public/contratos";
        $nombreCarpeta = $nombre_cliente . " " . $fechaActual;
        $rutaCarpeta = $rutaBase . '/' . $nombreCarpeta;
        echo $rutaCarpeta;
        if (!is_dir($rutaCarpeta)) {
            if (!mkdir($rutaCarpeta, 0777, true)) {
                dd($rutaCarpeta); 
                throw new Exception("Error al crear la carpeta"); // Lanza una excepción en caso de error
            }
        }

        return $rutaCarpeta;
    }

    public function generarDiferimiento($contrato, $numero_sucesivo, $ciudad, $numCedula, $fechaActual, $nombre_cliente, $rutaSaveContrato)
    {
        global $meses;
        list($ano, $mes, $dia) = explode('-', $fechaActual);
        $fechaFormateada = $dia . " de " . $meses[intval($mes)]  . " del " . $ano;
        $templateWord = new TemplateProcessor(resource_path("docs/DIFERIMIENTO QORIT.docx"));
        $nombre_cliente = strtoupper($nombre_cliente);
        $ciudad = strtoupper($ciudad);
        $templateWord->setValue('edit_contrato_id', $contrato);
        $templateWord->setValue('edit_num_cliente', $numero_sucesivo);
        $templateWord->setValue('edit_ciudad', $ciudad);
        $templateWord->setValue('edit_numero_cedula', $numCedula);
        $templateWord->setValue('edit_fecha_contrato', $fechaFormateada);
        $templateWord->setValue('edit_nombres_apellidos', $nombre_cliente);
        $nombreArchivo = 'QTDiferimiento' . $numero_sucesivo . " " . $nombre_cliente . '.docx';
        $pathToSave = $rutaSaveContrato . '/' . $nombreArchivo;
        $templateWord->saveAs($pathToSave);
        return $nombreArchivo;
    }
    public function generarVerificacion($nombre_cliente, $numero_sucesivo, $numCedula, $rutaSaveContrato)
    {
        $nombre_cliente = strtoupper($nombre_cliente);
        $templateWord = new TemplateProcessor(resource_path("docs/VERIFICACION.docx"));
        $templateWord->setValue('edit_nombres_apellidos', $nombre_cliente);
        $templateWord->setValue('edit_numero_cedula', $numCedula);
        $nombreArchivo = 'QTVerificacion' . $numero_sucesivo . " " . $nombre_cliente . '.docx';
        $pathToSave = $rutaSaveContrato . '/' . $nombreArchivo;


        $templateWord->saveAs($pathToSave);
        return $nombreArchivo;
    }
    public function generarFechasPagare($fecha_inicial, $valor, $abono, $numCuotas)
    {
        $fecha = new DateTime($fecha_inicial);

        $valor = $valor - $abono;
        $monto_cuota = number_format(($valor) / $numCuotas, 2);
        $resultados = array();
        for ($i = 0; $i < $numCuotas; $i++) {
            $saldo_restante = number_format($valor - ($i * $monto_cuota), 2);
            $resultados[] = array(
                'fecha' => $fecha->format('Y-m-d'),
                'monto' => $monto_cuota,
                'saldo_restante' => $saldo_restante,
                'num_cuota' => $i + 1,
                'saldo_post_pago' => number_format($valor - (($i + 1) * $monto_cuota), 2)
            );
            $fecha->add(new DateInterval('P1M'));
        }
        if ($resultados[$numCuotas - 1]['saldo_post_pago'] != 0) {
            $resultados[$numCuotas - 1]['saldo_post_pago'] = 0;
            $resultados[$numCuotas - 1]['monto'] = number_format($valor - (($numCuotas - 1) * $monto_cuota), 2);
        }
        return $resultados;
    }
    public function generarPagaresCredito($fechaInicio, $monto, $abono, $numCuotas, $rutaSaveContrato, $numero_sucesivo, $nombre_cliente, $ciudad, $numCedula, $fechaActual, $email)
    {
        global $meses;
        list($ano, $mes, $dia) = explode('-', $fechaActual);
        $nombre_cliente = strtoupper($nombre_cliente);
        $ciudadMayu = strtoupper($ciudad);
        $ciudad = ucwords($ciudad);
        $numCedula = strtoupper($numCedula);
        $fmt = new NumberFormatter('es', NumberFormatter::SPELLOUT);
        $montoSaldoPrevText = $fmt->format($monto);
        $montoSaldoPrevText = strtoupper($montoSaldoPrevText);
        $fechaFormateada = $dia . " días del mes de " . $meses[intval($mes)] . " de " . $ano;
        if ($numCuotas == 12) {
            $templateWord = new TemplateProcessor(resource_path("docs/PAGARÉ CREDITO DIRECTO 12.docx"));
            $listaFechasPagare = $this->generarFechasPagare($fechaInicio, $monto, $abono, $numCuotas);
        }
        if ($numCuotas == 24) {
            $templateWord = new TemplateProcessor(resource_path("docs/PAGARÉ CREDITO DIRECTO 24.docx"));
            $listaFechasPagare = $this->generarFechasPagare($fechaInicio, $monto, $abono, $numCuotas);
        }
        if ($numCuotas == 36) {
            $templateWord = new TemplateProcessor(resource_path("docs/PAGARÉ CREDITO DIRECTO 36.docx"));
            $listaFechasPagare = $this->generarFechasPagare($fechaInicio, $monto, $abono, $numCuotas);
        }
        for ($i = 1; $i <= $numCuotas; $i++) {
            $templateWord->setValue("edit_saldo_prev_{$i}", $listaFechasPagare[$i - 1]["saldo_restante"]);
            $templateWord->setValue("edit_fecha_pago_{$i}", $listaFechasPagare[$i - 1]["fecha"]);
            $templateWord->setValue("edit_cuotas_rest_{$i}", $listaFechasPagare[$i - 1]["num_cuota"]);
            $templateWord->setValue("edit_pago_mensual_{$i}", $listaFechasPagare[$i - 1]["monto"]);
            $templateWord->setValue("edit_pago_final_{$i}", $listaFechasPagare[$i - 1]["saldo_post_pago"]);
        }
        $templateWord->setValue('edit_nombres_apellidos', $nombre_cliente);
        $templateWord->setValue('edit_numero_cedula', $numCedula);
        $templateWord->setValue('edit_ciudad', $ciudad);
        $templateWord->setValue('edit_ciudad_mayu', $ciudadMayu);
        $templateWord->setValue('edit_saldo_prev_1_text', $montoSaldoPrevText);
        $templateWord->setValue('edit_fecha_texto', $fechaFormateada);
        $templateWord->setValue('edit_email', $email);
        $templateWord->setValue('edit_monto_contrato', $monto);
        $nombreArchivo = 'QTPagareCreditos' . $numero_sucesivo . " " . $nombre_cliente . '.docx';
        $pathToSave = $rutaSaveContrato . '/' . $nombreArchivo;
        $templateWord->saveAs($pathToSave);
        return $nombreArchivo;
    }
    public function generarBeneficiosAlcance($contrato, $numero_sucesivo, $nombre_cliente, $numCedula, $bonoQory, $bonoQoryInt, $certificadoVacacionalInternacional, $bonoSemanaInternacional, $rutaSaveContrato, $clausulaCDBoolean, $destinoInternacional, $numPersonasInternacional)
    {

        $valorClausula = 15;
        $nombre_cliente = strtoupper($nombre_cliente);
        $titulo_nacional1 = ". BONO DE HOSPEDAJE NACIONAL 1 QORY LOYALTY: ";
        $texto_nacional1 = "Acepto y recibo UN Bono de Hospedaje 1 Noches 2 Días para 06 personas. Previo pago de Impuestos. Uso exclusivo en departamentos de la compañía. No incluye ningún tipo de alimentación.";
        $titulo_nacional2 = ".	BONO DE HOSPEDAJE NACIONAL 2 QORY LOYALTY: ";
        $texto_nacional2 = "Acepto y recibo UN Bono de Hospedaje 2 Noches 3 Días para 06 personas. Previo pago de Impuestos. Uso exclusivo en departamentos de la compañía. No incluye ningún tipo de alimentación.";
        $clausulaCD = "";
        $titulo_certificado_vacacional_internacional = ". CERTIFICADO VACACIONAL INTERNACIONAL QORY LOYALTY";
        $texto_certificado_vacacional_internacional = "Acepto y recibo un Bono de Hospedaje 5 Días 4 Noches para 02 Adultos. Previo pago de Impuestos. NO Incluye la alimentación. PREVIA RESERVA. Destino: " . $destinoInternacional;
        $titulo_semana_internacional = ". SEMANA INTERNACIONAL QORY LOYALTY";
        $texto_semana_internacional = "Acepto y recibo un Bono de Hospedaje 8 días y 7 noches para $numPersonasInternacional personas. Previo pago de Impuestos. NO Incluye la alimentación. PREVIA RESERVA. Destino: " . $destinoInternacional;

        if ($clausulaCDBoolean) {
            $clausulaCD = "Los beneficios se habilitarán conforme al contrato de programa turístico suscrito y al reglamento interno de QORIT TRAVEL AGENCY S.A.";
        }

        $templateWord = new TemplateProcessor(resource_path("docs/ANEXO 3 BENEFICIOS ALCANCE DE LA OFERTA.docx"));
        $templateWord->setValue('edit_nombres_apellidos', $nombre_cliente);
        $templateWord->setValue('edit_contrato_id', $contrato);
        $templateWord->setValue('edit_numero_cedula', $numCedula);
        $templateWord->setValue('edit_num_cliente', $numero_sucesivo);
        $templateWord->setValue('edit_beneficios_alcance', $clausulaCD);

        if ($bonoQory) {
            $valorClausula++;
            $templateWord->setValue('edit_titulo_nacional1', $valorClausula . " " . $titulo_nacional1);
            $templateWord->setValue('edit_texto_bono_hospedaje', $texto_nacional1);
        } else {
            $templateWord->setValue('edit_titulo_nacional1', "");
            $templateWord->setValue('edit_texto_bono_hospedaje', "");
        }
        if ($bonoQoryInt) {
            $valorClausula++;
            $templateWord->setValue('edit_titulo_nacional2', $valorClausula . " " . $titulo_nacional2);
            $templateWord->setValue('edit_texto_vacacional', $texto_nacional2);
        } else {
            $templateWord->setValue('edit_titulo_nacional2', "");
            $templateWord->setValue('edit_texto_vacacional', "");
        }
        if ($certificadoVacacionalInternacional) {
            $valorClausula++;
            $templateWord->setValue('edit_titulo_vacacional_internacional', $valorClausula . " " . $titulo_certificado_vacacional_internacional);
            $templateWord->setValue('edit_texto_vacacional_internacional', $texto_certificado_vacacional_internacional);
        } else {
            $templateWord->setValue('edit_titulo_vacacional_internacional', "");
            $templateWord->setValue('edit_texto_vacacional_internacional', "");
        }
        if ($bonoSemanaInternacional) {
            $valorClausula++;
            $templateWord->setValue('edit_titulo_semana_internacional', $valorClausula . " " . $titulo_semana_internacional);
            $templateWord->setValue('edit_texto_semana_internacional', $texto_semana_internacional);
        } else {
            $templateWord->setValue('edit_titulo_semana_internacional', "");
            $templateWord->setValue('edit_texto_semana_internacional', "");
        }

        $nombreArchivo = 'QTBeneficiosDeAlcance' . $numero_sucesivo . " " . $nombre_cliente . '.docx';
        $pathToSave = $rutaSaveContrato . '/' . $nombreArchivo;
        $templateWord->saveAs($pathToSave);
        return $nombreArchivo;
    }
    public function generarContrato($contrato, $nombre_cliente, $numero_sucesivo, $numCedula, $montoContrato, $aniosContrato, $formasPago, $email, $fechaActual, $ciudad, $rutaSaveContrato)
    {
        $formasPagoS = "";
        $formasPagoArray = array();
        foreach ($formasPago as $forma) {
            $formasPagoS .= $forma . "\n";
            $formasPagoArray[] = $forma;
        }

        global $meses;
        list($ano, $mes, $dia) = explode('-', $fechaActual);
        $fechaFormateada = $dia . " de " . $meses[intval($mes)]  . " del " . $ano;
        $nombre_cliente = strtoupper($nombre_cliente);
        $fmt = new NumberFormatter('es', NumberFormatter::SPELLOUT);
        $montoContratoText = $fmt->format($montoContrato);
        $aniosContratoText = $fmt->format($aniosContrato);
        $aniosContratoText = strtoupper($aniosContratoText);
        $montoContratoText = strtoupper($montoContratoText);
        $templateWord = new TemplateProcessor(resource_path("docs/Contrato de agencia de viajes_QORIT.docx"));
        $templateWord->setValue('edit_nombres_apellidos', $nombre_cliente);
        $templateWord->setValue('edit_contrato_id', $contrato);
        $templateWord->setValue('edit_num_cliente', $numero_sucesivo);
        $templateWord->setValue('edit_numero_cedula', $numCedula);
        $templateWord->setValue('edit_monto_contrato', $montoContrato);
        $templateWord->setValue('edit_anios_contrato', $aniosContrato);
        for ($i = 1; $i <= count($formasPagoArray); $i++) {
            $templateWord->setValue("edit_forma_pago_$i", $formasPagoArray[$i - 1]);
        }
        for ($i = count($formasPagoArray); $i <= 5; $i++) {
            $templateWord->setValue("edit_forma_pago_$i", "");
        }
        $templateWord->setValue('edit_email', $email);
        $templateWord->setValue('edit_ciudad', $ciudad);
        $templateWord->setValue('edit_texto_anios_contrato', $aniosContratoText);
        $templateWord->setValue('edit_monto_contrato_texto', $montoContratoText);
        $templateWord->setValue('edit_fecha_texto', $fechaFormateada);
        $nombreArchivo = 'QTContrato' . $numero_sucesivo . " " . $nombre_cliente . '.docx';
        $pathToSave = $rutaSaveContrato . '/' . $nombreArchivo;
        $templateWord->saveAs($pathToSave);
        return $nombreArchivo;
    }
    public function generarContratoCreditoDirecto($contrato, $nombre_cliente, $numero_sucesivo, $numCedula, $montoContrato, $aniosContrato, $formasPago, $email, $fechaActual, $ciudad, $rutaSaveContrato, $abonoCD, $numCuotasCD, $valorCuotaCD)
    {
        global $meses;
        list($ano, $mes, $dia) = explode('-', $fechaActual);
        $fechaFormateada = $dia . " de " . $meses[intval($mes)]  . " del " . $ano;
        $nombre_cliente = strtoupper($nombre_cliente);
        $fmt = new NumberFormatter('es', NumberFormatter::SPELLOUT);
        $montoContratoText = $fmt->format($montoContrato);
        $aniosContratoText = $fmt->format($aniosContrato);
        $abonoContratoText = $fmt->format($abonoCD);
        $valorCuotaDolares = floor($valorCuotaCD);
        $valorCentavosDolares = round(($valorCuotaCD - $valorCuotaDolares) * 100);
        $valorCuotaDolaresText = $fmt->format($valorCuotaDolares);
        $valorCentavosDolaresText = $fmt->format($valorCentavosDolares);
        $cuotaValorContratoText = $valorCuotaDolaresText . " con " . $valorCentavosDolaresText;
        $aniosContratoText = strtoupper($aniosContratoText);
        $montoContratoText = strtoupper($montoContratoText);
        $abonoContratoText = strtoupper($abonoContratoText);
        $cuotaValorContratoText = strtoupper($cuotaValorContratoText);
        $templateWord = new TemplateProcessor(resource_path("docs/Contrato de agencia de viajes_QORIT CD.docx"));
        $templateWord->setValue('edit_nombres_apellidos', $nombre_cliente);
        $templateWord->setValue('edit_contrato_id', $contrato);
        $templateWord->setValue('edit_num_cliente', $numero_sucesivo);
        $templateWord->setValue('edit_numero_cedula', $numCedula);
        $templateWord->setValue('edit_monto_contrato', $montoContrato);
        $templateWord->setValue('edit_anios_contrato', $aniosContrato);
        $templateWord->setValue('edit_email', $email);
        $templateWord->setValue('edit_ciudad', $ciudad);
        $templateWord->setValue('edit_texto_anios_contrato', $aniosContratoText);
        $templateWord->setValue('edit_monto_contrato_texto', $montoContratoText);
        $templateWord->setValue('edit_fecha_texto', $fechaFormateada);
        $templateWord->setValue('edit_abono_CD', $abonoCD);
        $templateWord->setValue('edit_abono_letras_CD', $abonoContratoText);
        $templateWord->setValue('edit_num_coutas_CD', $numCuotasCD);
        $templateWord->setValue('edit_monto_cuota_CD', $valorCuotaCD);
        $templateWord->setValue('edit_monto_cuota_letas_CD', $cuotaValorContratoText);
        $nombreArchivo = 'QTContratoCD' . $numero_sucesivo . " " . $nombre_cliente . '.docx';
        $pathToSave = $rutaSaveContrato . '/' . $nombreArchivo;
        $templateWord->saveAs($pathToSave);
        return $nombreArchivo;
    }
    public function generarPagare($nombre_cliente, $numCedula, $numero_sucesivo, $fechaVencimiento, $ciudad, $email, $valor_pagare, $fechaActual, $numCuotas, $montoCuotaPagare, $pagareText, $rutaSaveContrato)
    {
        global $meses;
        list($ano, $mes, $dia) = explode('-', $fechaActual);
        list($ano2, $mes2, $dia2) = explode('-', $fechaVencimiento);
        $fechaFormateada = $dia . " de " . $meses[intval($mes)]  . " del " . $ano;
        $fechaFormatVencimiento = $dia2 . ' DE ' . strtoupper($meses[intval($mes2)]) . ' DEL ' . $ano2;
        $nombre_cliente = strtoupper($nombre_cliente);
        $fmt = new NumberFormatter('es', NumberFormatter::SPELLOUT);
        $pagareText = $fmt->format($valor_pagare);
        $pagareText = strtoupper($pagareText);
        $montoCuotaPagare = ($valor_pagare / $numCuotas);
        $templateWord = new TemplateProcessor(resource_path("docs/PAGARE QORIT.docx"));
        $templateWord->setValue('edit_nombres_apellidos', $nombre_cliente);
        $templateWord->setValue('edit_numero_cedula', $numCedula);
        $templateWord->setValue('edit_fecha_vencimiento', $fechaFormatVencimiento);
        $templateWord->setValue('edit_ciudad', $ciudad);
        $templateWord->setValue('edit_email', $email);
        $templateWord->setValue('edit_num_cuotas', $numCuotas);
        $templateWord->setValue('edit_monto_pagare_text', $pagareText);
        $templateWord->setValue('edit_fecha_texto', $fechaFormateada);
        $templateWord->setValue('edit_monto_cuota_pagare', $montoCuotaPagare);
        $templateWord->setValue('edit_monto_pagare', $valor_pagare);
        $nombreArchivo = 'QTPagare' . $numero_sucesivo . " " . $nombre_cliente . '.docx';
        $pathToSave = $rutaSaveContrato . '/' . $nombreArchivo;
        $templateWord->saveAs($pathToSave);
        return $nombreArchivo;
    }
    public function generarCheckList($contrato, $numero_sucesivo, $ciudad, $provincia,  $numCedula, $email, $fechaActual, $nombre_cliente, $ubicacionSala, $rutaSaveContrato, $credDirBoolean)
    {
        global $meses;
        $nombre_cliente = strtoupper($nombre_cliente);
        $ubicacionSala = strtoupper($ubicacionSala);
        $ciudadMayu = strtoupper($ciudad);
        $ciudad = ucwords($ciudad);
        global $meses;
        $textoAnexo2 =  "PARA PAGOS CON TARJETA";
        if ($credDirBoolean) {
            $textoAnexo2 = "Debito Automatico";
        }
        $textoAnexo2 = strtoupper($textoAnexo2);
        list($ano, $mes, $dia) = explode('-', $fechaActual);
        $fechaFormateada = $dia . " de " . $meses[intval($mes)]  . " del " . $ano;
        $templateWord = new TemplateProcessor(resource_path("docs/CHECK LIST QORIT.docx"));
        $templateWord->setValue('edit_contrato_id', $contrato);
        $templateWord->setValue('edit_num_cliente', $numero_sucesivo);
        $templateWord->setValue('edit_ciudad', $ciudad);
        $templateWord->setValue('edit_ciudad_mayu', $ciudadMayu);
        $templateWord->setValue('edit_provincia', $provincia);
        $templateWord->setValue('edit_fecha_contrato', $fechaActual);
        $templateWord->setValue('edit_nombres_apellidos', $nombre_cliente);
        $templateWord->setValue('edit_numero_cedula', $numCedula);
        $templateWord->setValue('edit_sala_lugar', $ubicacionSala);
        $templateWord->setValue('edit_email', $email);
        $templateWord->setValue('edit_fecha_texto', $fechaFormateada);
        $templateWord->setValue('edit_anexo2_CD', $textoAnexo2);
        $nombreArchivo = 'QTCheckList' . $numero_sucesivo . " " . $nombre_cliente . '.docx';
        $pathToSave = $rutaSaveContrato . '/' . $nombreArchivo;
        $templateWord->saveAs($pathToSave);
        return $nombreArchivo;
    }
}

class Utils
{
    public function agregarPago($vendedor, $contrato, $montoPagado, $controlerPV)
    {
        $tipoVendedor = $vendedor->rol;
        if ($contrato->closer1 && $contrato->closer2 && $vendedor->rol == "Closer") {
            $tipoVendedor = "Closer2";
        }
        $pagoVendedor = new PagoVendedor();
        $pagoVendedor->valor_pago = $controlerPV->obtenerValorPago($tipoVendedor, $montoPagado);
        $pagoVendedor->fecha_pago = new DateTime('now');
        $pagoVendedor->concepto = "Participación Contrato" . $contrato->contrato_id;
        $pagoVendedor->estado = "Pendiente";
        $vendedor->pagosVendedor()->save($pagoVendedor);
    }
    public function obtenerNumeroMayor()
    {
        $resultado = Contrato::select(DB::raw('MAX(contrato_id) AS max_contrato_id'))->first();

        if ($resultado) {
            $maxNumero = intval($resultado->max_contrato_id);
            $numero_sucesivo = $maxNumero + 1;
        } else {
            $numero_sucesivo = 1; // Si no hay contratos en la base de datos
            echo ("no está entrando dentro del if");
        }

        return $numero_sucesivo;
    }

    public function obtenerNumeroMayorTipo($tipoContrato)
    {
        $resultadoA = Contrato::pluck('contrato_id')->toArray();
        $ultimos_cinco_caracteres = array_map(function ($contrato_id) {
            return substr($contrato_id, -5); // Obtener los últimos 5 caracteres de cada contrato_id
        }, $resultadoA);
        $lista_30000_39999 = [30000];
        $lista_40000_49999 = [40000];

        foreach ($ultimos_cinco_caracteres as $valor) {
            $numero = intval($valor);
            if ($numero >= 30000 && $numero <= 39999) {
                $lista_30000_39999[] = $valor;
            } elseif ($numero >= 40000 && $numero <= 49999) {
                $lista_40000_49999[] = $valor;
            }
        }

        if ($tipoContrato == "Sala 1") {
            $maximoSala1 =  max($lista_40000_49999) + 1;
            return $maximoSala1;
        }
        if ($tipoContrato == "Sala 2") {
            $maximoSala2 = max($lista_30000_39999) + 1;
            return $maximoSala2;
        }
        return 0;
    }
    public function comprimirArchivos($ruta, $listaArchivos)
    {
        $zip = new ZipArchive();
        $nombreArchivoZip = $ruta . '.zip';
        if ($zip->open($nombreArchivoZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($listaArchivos as $elemento) {
                $archivo = $ruta . '/' . $elemento;
                if (is_file($archivo)) {
                    $zip->addFile($archivo, $elemento);
                } else {
                    echo "El elemento $elemento no es un archivo válido. Se omitirá.\n";
                }
            }
        } else {
        }
        $zip->close();
    }
}
