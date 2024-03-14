<?php

namespace App\Http\Controllers;

use App\Events\RecibirMensaje;
use App\Models\WhatsApp;
use DateTime;
use Exception;
use App\Models\Notificacion;
use Illuminate\Http\Request;


class WhatsAppController extends Controller
{


    public function index()
    {
        $mensajes = WhatsApp::all();
        return view('chat.chat', ['mensajes' => $mensajes]);
    }

    public function enviarPHP(Request $request)
    {
        $mensaje = $request->mensajeEnvio;
        $numeroEnviar = $request->numeroEnvio;
        if (strncmp($numeroEnviar, '0', strlen('0')) === 0) {
            $numeroEnviar = '593' . substr($numeroEnviar, 1);
        }
        $telefonoEnviaID = "258780720641927";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://graph.facebook.com/v18.0/' . $telefonoEnviaID . '/messages',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode([
                "messaging_product" => "whatsapp",
                "to" => $numeroEnviar, // Número de teléfono del destinatario
                "type" => "text", // Tipo de mensaje
                "text" => [
                    "body" => $mensaje, // Cuerpo del mensaje de texto
                ],
            ]),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer EAA0cGBz1VmwBO4A6DDeewvO916Exi5FQjVwMqCspVC8GSiZBkRclhJJd2iPKRZADL99DWVDxDUxE1GL9jU9h5F68jPGjpx5LTc0xhNSSwuQfJb0htNHOlZCqltVTlxnRXRG9xvmVVQPegZC9jpFv2wFLOjvayGNdYZAtilQyHYddoetjm3NbkAmWbmdiGqJMW5RuDyHkZB7VsLbIlvXMXl'
            ),
        ));
        $response = curl_exec($curl);
        $whatsApp = new WhatsApp();
        $whatsApp->mensaje_enviado = $mensaje;
        $whatsApp->id_wa = "asdasdasd";
        $whatsApp->telefono_wa = "593987411818";
        $whatsApp->id_numCliente = $numeroEnviar;
        $whatsApp->fecha_hora = new DateTime('now');
        $whatsApp->visto = true;
        $whatsApp->save();
        curl_close($curl);
        return json_encode($whatsApp);
    }

    public function webhook(Request $request)
    {

        try {
            $verifyToken = 'TokenPruebaValidacion';
            $query = $request->query();
            $mode = $query['hub_mode'];
            $token = $query['hub_verify_token'];
            $challenge = $query['hub_challenge'];
            if ($mode && $token) {
                if ($mode === 'subscribe' && $token == $verifyToken) {
                    return response($challenge, 200)->header('Content-Type', 'text/plain');
                }
            }
            throw new Exception('Invalid Request');
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    /*
      * RECEPCION DE MENSAJES
      */
    public function recibe()
    {
        $respuesta = file_get_contents("php://input");
        if ($respuesta == null) {
            exit;
        }
        $respuesta = json_decode($respuesta, true);
        $telefonoUser =  $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['from'];
        $mensaje =  $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
        $id = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['id'];
        $timestamp = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];
        $whatsApp = new WhatsApp();
        $whatsApp->timestamp_wa = $timestamp;
        $whatsApp->mensaje_enviado  = $mensaje;
        $whatsApp->id_wa = $id;
        $whatsApp->visto = false;
        $whatsApp->telefono_wa = $telefonoUser;
        $whatsApp->id_numCliente = $telefonoUser;
        $whatsApp->fecha_hora = new DateTime('now');
        $whatsApp->save();
        $event = new RecibirMensaje($telefonoUser, $mensaje, $whatsApp->fecha_hora);
        $cadenaNotificacion = 'Mensaje de : ' . $telefonoUser;
        $this->crearNotificacion($cadenaNotificacion, $mensaje);
        event($event);
    }

    public function notificacionMensaje(Request $request)
    {
        $mensajes = WhatsApp::all();
        // Crea una nueva tarea
        $mensaje = new WhatsApp;
        $mensaje->id_numCliente = $request->id_numCliente;
        $mensaje->mensaje_recibido = $request->mensaje_recibido;
        $mensaje->save();


        // Devolver la vista con los mensajes
        return view('chat.chat', compact('mensajes'));
        // Pasa los mensajes a la vista 'dashboard'
    }

    public function show(WhatsApp $whatsApp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WhatsApp $whatsApp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WhatsApp $whatsApp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WhatsApp $whatsApp)
    {
        //
    }
    public function crearNotificacion($mensaje, $comentario)
    {
        $notificacion = new Notificacion;
        $notificacion->descripcion = $mensaje;
        $notificacion->comentario = $comentario;
        $notificacion->visto = false;
        $notificacion->tipo = "chat"; 
        $notificacion->save();
    }
}
class Utils
{
    function convertirMinNoTilde($mensaje)
    {
        $mensaje = mb_strtolower($mensaje, 'UTF-8');
        $mensaje = str_replace(array('á', 'é', 'í', 'ó', 'ú', 'ü'), array('a', 'e', 'i', 'o', 'u', 'u'), $mensaje);
        return $mensaje;
    }
}
