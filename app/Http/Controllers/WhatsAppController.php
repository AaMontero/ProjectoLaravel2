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
        return $this->enviarMensaje($numeroEnviar, $mensaje);
    }


    public function enviarMensajeChatBot($numeroEnviar, $mensajeLlega)
    {

        if (strncmp($numeroEnviar, '0', strlen('0')) === 0) {
            $numeroEnviar = '593' . substr($numeroEnviar, 1);
        }
        if ($mensajeLlega == "imagen") {
            $mensaje =  $this->conversacion("Listo");
        } else {
            $mensaje  = $this->conversacion($mensajeLlega);
        }

        if (gettype($mensaje) != 'array') {
            $this->enviarMensaje($numeroEnviar, $mensaje);
        } else {
            foreach ($mensaje as $elem) {
                $this->enviarMensaje($numeroEnviar, $elem);
                sleep(30);
            }
        }
    }

    function enviarMensaje($numeroEnviar, $mensaje)
    {
        $telefonoEnviaID = getenv('WPP_ID');
        $apiUrl = 'https://graph.facebook.com/v' . getenv('WPP_VERSION') . '/';
        $apiKey = getenv('WPP_TOKEN');
        if (strncmp($numeroEnviar, '0', strlen('0')) === 0) {
            $numeroEnviar = '593' . substr($numeroEnviar, 1);
        }


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, true);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $apiUrl . $telefonoEnviaID . '/messages',
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
                'Authorization: Bearer ' . $apiKey,
            ),
        ));
        $response = curl_exec($curl);
        $idMensajeEnviar = json_decode($response, true)['messages'][0]['id'];
        $whatsApp = new WhatsApp();
        $whatsApp->mensaje_enviado = $mensaje;
        $whatsApp->id_wa = $idMensajeEnviar;
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
        if ($respuesta === false) {
            exit; // Salir si no se reciben datos
        }
        $respuesta = json_decode($respuesta, true);
        $mensaje = $respuesta['entry'][0]['changes'][0]['value']['messages'][0];
        $tipo = $mensaje['type'];
        $id = $mensaje['id'];
        $telefonoUser = $mensaje['from'];
        $timestamp = $mensaje['timestamp'];
        $sqlCantidad = WhatsApp::where('id_wa', $id)->count();
        if ($sqlCantidad == 0) {
            if ($tipo == "audio") {
                $audio = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['audio'];
                $idAudio = $audio['id'];
                $responseAudio = $this->obtenerMultimedia($idAudio);
                $directorio = 'uploads/audiosWpp/' . $telefonoUser;
                $rutaAudio = $directorio . '/' . $timestamp . 'audio.ogg';

                if (!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                file_put_contents($rutaAudio, $responseAudio);
                $mensaje = '{"rutaAudio": "' . $rutaAudio . '"}';
            } elseif ($tipo == "image") {
                $imagen = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['image'];
                $idImagen = $imagen['id'];
                $textoImagen = isset($imagen['caption']) ?
                    $imagen['caption'] : "";
                $responseIMG = $this->obtenerMultimedia($idImagen);
                $directorio = 'uploads/imagenesWpp/' . $telefonoUser;
                $rutaImagen = $directorio . '/' . $timestamp . 'imagen.jpeg';
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                file_put_contents($rutaImagen, $responseIMG);
                $mensaje = '{"ruta": "' . $rutaImagen . '", "textoImagen": "' . $textoImagen . '"}';
                //$this->enviarMensajeChatBot($telefonoUser, "imagen");
            } else {
                $mensaje = isset($respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) ?
                    $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] : "";
                $this->enviarMensajeChatBot($telefonoUser, $mensaje);
            }
            $whatsApp = new WhatsApp();
            $whatsApp->timestamp_wa = $timestamp;
            $whatsApp->mensaje_enviado  = $mensaje;
            $whatsApp->id_wa = $id;
            $whatsApp->visto = false;
            $whatsApp->telefono_wa = $telefonoUser;
            $whatsApp->id_numCliente = $telefonoUser;
            $whatsApp->fecha_hora = new DateTime('now');
            $whatsApp->save();

            // Evento de recibir mensaje
            $event = new RecibirMensaje($telefonoUser, $mensaje, $whatsApp->fecha_hora);
            $cadenaNotificacion = 'Mensaje de : ' . $telefonoUser;
            $this->crearNotificacion($cadenaNotificacion, $mensaje);
            event($event);
        } else {
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
        if ($mensaje->startwWith('{"ruta"')) {
            $mensaje = "Ha recibido un archivo multimedia";
        }
        $notificacion = new Notificacion;
        $notificacion->descripcion = $mensaje;
        $notificacion->comentario = $comentario;
        $notificacion->visto = false;
        $notificacion->tipo = "chat";
        $notificacion->save();
    }
    function convertirMinNoTilde($mensaje)
    {
        $mensaje = mb_strtolower($mensaje, 'UTF-8');
        $mensaje = str_replace(array('á', 'é', 'í', 'ó', 'ú', 'ü'), array('a', 'e', 'i', 'o', 'u', 'u'), $mensaje);
        return $mensaje;
    }
    function obtenerMultimedia($idMedia)
    {
        $url = 'https://graph.facebook.com/v' . getenv('WPP_MULTIVERSION') . '/' . $idMedia;
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . getenv('WPP_TOKEN')
            ),
        ));
        $response = curl_exec($curl);
        $responseData = json_decode($response, true);
        $urlDescarga = isset($responseData['url']) ? $responseData['url'] : "";
        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_URL => $urlDescarga,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_USERAGENT => 'PostmanRuntime/7.34.0',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . getenv('WPP_TOKEN')
            ),
        ));
        return curl_exec($curl2);
    }


    function conversacion($mensajeRecibido)
    {

        $util = new Utils();

        $mensajenoTilde = $util->convertirMinNoTilde($mensajeRecibido);
        switch ($mensajenoTilde) {
            case $util->convertirMinNoTilde("Hola, ayúdame con información"):
                return "¡Encantado! ¿Con quién tengo el gusto?";
                break;
            case $util->convertirMinNoTilde("Me llamo Paul"):
                return "Mucho gusto, Paul. ¿En qué puedo ayudarte?";
                break;
            case $util->convertirMinNoTilde("Quiero viajar a México, ¿qué me recomendarías?"):
                return "Claro, contamos con el siguiente paquete de viaje a México-Cancún:\nPaquete: Grand Oasis Cancún\nDuración: 4 días y 3 noches\nPrecio: $450\nServicios: Traslado aeropuerto CUN – Hotel – aeropuerto CUN.\n- Participación en actividades y entretenimiento del hotel.\n- Sistema de alimentación todo incluido.\n- Habitación Gran Estándar.\n\n¿Te gustaría reservar este paquete o necesitas más información?";
                break;
            case $util->convertirMinNoTilde("¿Cuáles son las fechas disponibles para este paquete?"):
                return "Las fechas disponibles para el paquete Grand Oasis Cancún, México son: del 10 al 20 de agosto, del 20 al 31 de septiembre y del 5 al 15 de octubre. ¿Te gustaría reservar?";
                break;
            case $util->convertirMinNoTilde("Me gustaría reservar para el 10 al 15 de agosto"):
                return "Perfecto, ¿cuántas personas serán?";
                break;
            case $util->convertirMinNoTilde("Dos personas"):
                return "El valor para la reserva del paquete Grand Oasis Cancún es de $450. Confirma tu reserva, por favor proporcionándome los nombres de los pasajeros, un número de cédula y un correo electrónico.";
                break;
            case $util->convertirMinNoTilde("Paul Romero y Carla Garzon, CI: 1234567890, paulromero90@gmail.com"):
                return "Gracias, el valor de tu reserva sería $450. Tenemos diferentes formas de pago:\n1- Transferencia\n2- Pago con tarjeta de crédito (PayPhone)\nO puedes solicitar contactarte con uno de nuestros asesores especializados.";
                break;
            case $util->convertirMinNoTilde("Voy a realizar mi pago con una tarjeta de crédito"):
                return "Para finalizar el proceso, por favor haz clic en el siguiente enlace para proceder con el pago: https://www.payphone.app/.\n\nUna vez completado el pago, recibirás un correo electrónico con todos los detalles de tu pago.";
                break;
            case $util->convertirMinNoTilde("En este momento ya realicé el pago"):
                return "Por favor, envía una captura de pantalla del comprobante de pago.";
                break;
            case $util->convertirMinNoTilde("Listo"):
                $mensaje1 = "En este momento estamos verificando el pago...";
                $mensaje2 = "Tu pago se ha creditado correctamente. Recibirás un correo electrónico con más detalles de tu viaje.";
                $retorno = [$mensaje1, $mensaje2];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Gracias"):
                return "Gracias a ti. ¡Buen viaje!";
                break;
            default:
                return "No hay respuesta";
        }
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
