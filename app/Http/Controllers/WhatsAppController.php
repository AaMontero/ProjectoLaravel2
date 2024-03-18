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
        $apiUrl = 'https://graph.facebook.com/v18.0/';
        $apiKey = 'EAA0cGBz1VmwBOzqlCBUHzv9mf4BsmNAqw2rLoreXSXUnxVL50mouIhdcAZAWZBLsKnqZBuRWiPcQWSE325mRwtcWbQMKsABhZAopcKgBKq6m0zsS8G0nQ7FJkZBDexVQPdZCtG7BzWRZBCwWGDAQNv32Jm0dulyiGSKOBrZBLZA7gmnxzszGg8L95fWLMiGeV1g2x';
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
                'Authorization: Bearer EAA0cGBz1VmwBOzqlCBUHzv9mf4BsmNAqw2rLoreXSXUnxVL50mouIhdcAZAWZBLsKnqZBuRWiPcQWSE325mRwtcWbQMKsABhZAopcKgBKq6m0zsS8G0nQ7FJkZBDexVQPdZCtG7BzWRZBCwWGDAQNv32Jm0dulyiGSKOBrZBLZA7gmnxzszGg8L95fWLMiGeV1g2x'
            ),
        ));
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
            $verifyToken = 'TokenVerificacion';
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
        //Archivo llegando bien
        $respuesta = json_decode($respuesta, true);
        $tipo = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['type'];
        //Tipo de mensaje que llega
        if ($tipo == "image") {
            //Esta entrando dentro del imagen
            $idImagen =  $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['image']['id'];
            $textoImagen = "";
            if (isset($respuesta['entry'][0]['changes'][0]['value']['messages'][0]['image']['caption'])) {
                $textoImagen = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['image']['caption'];
            };
            $url = "https://graph.facebook.com/v19.0/" . $idImagen;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer EAA0cGBz1VmwBOzqlCBUHzv9mf4BsmNAqw2rLoreXSXUnxVL50mouIhdcAZAWZBLsKnqZBuRWiPcQWSE325mRwtcWbQMKsABhZAopcKgBKq6m0zsS8G0nQ7FJkZBDexVQPdZCtG7BzWRZBCwWGDAQNv32Jm0dulyiGSKOBrZBLZA7gmnxzszGg8L95fWLMiGeV1g2x'
                ),
            ));
            $response = curl_exec($curl);
            $responseData = json_decode($response, true);
            $urlDescarga = $responseData['url']; // Esta llegando la URL 
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlDescarga,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_USERAGENT => 'PostmanRuntime/7.34.0',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer EAA0cGBz1VmwBOzqlCBUHzv9mf4BsmNAqw2rLoreXSXUnxVL50mouIhdcAZAWZBLsKnqZBuRWiPcQWSE325mRwtcWbQMKsABhZAopcKgBKq6m0zsS8G0nQ7FJkZBDexVQPdZCtG7BzWRZBCwWGDAQNv32Jm0dulyiGSKOBrZBLZA7gmnxzszGg8L95fWLMiGeV1g2x'
                ),
            ));

            $id = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['id'];
            $telefonoUser =  $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['from'];
            $timestamp = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];
            $rutaImagen = 'uploads/imagenesWpp/' . $telefonoUser . '/' . $timestamp . 'imagen.jpeg';
            $directorio = 'uploads/imagenesWpp/' . $telefonoUser;
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $mensajeImagen = '{"ruta": "' . $rutaImagen . '", "textoImagen": "' . $textoImagen . '"}';

            //Hacer la ruta para la imagen
            $responseImagen = curl_exec($curl);
            $whatsApp = new WhatsApp();
            $whatsApp->timestamp_wa = $timestamp;
            $whatsApp->mensaje_enviado  = $mensajeImagen;
            $whatsApp->id_wa = $id;
            $whatsApp->visto = false;
            $whatsApp->telefono_wa = $telefonoUser;
            $whatsApp->id_numCliente = $telefonoUser;
            $whatsApp->fecha_hora = new DateTime('now');
            $whatsApp->save();
            $event = new RecibirMensaje($telefonoUser, $mensajeImagen, $whatsApp->fecha_hora);
            $cadenaNotificacion = 'Mensaje de : ' . $telefonoUser;
            $this->crearNotificacion($cadenaNotificacion, "Se ha recibido una imagen");
            event($event);
            file_put_contents($rutaImagen, $responseImagen);
        } else {
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
    }

    public function leerMensajesUsuario($numeroUsuario)
    {
        Whatsapp::where('id_numCliente', $numeroUsuario)
            ->update(['visto' => 1]);
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
