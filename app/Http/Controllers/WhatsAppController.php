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

    public function enviarMensajeChatBot($numeroEnviar, $mensajeLlega)
    {
        $mensaje  = $this->conversacion($mensajeLlega);
        if (strncmp($numeroEnviar, '0', strlen('0')) === 0) {
            $numeroEnviar = '593' . substr($numeroEnviar, 1);
        }

        $telefonoEnviaID = "258780720641927";
        $apiUrl = 'https://graph.facebook.com/v18.0/';
        $apiKey = 'EAA0cGBz1VmwBOzqlCBUHzv9mf4BsmNAqw2rLoreXSXUnxVL50mouIhdcAZAWZBLsKnqZBuRWiPcQWSE325mRwtcWbQMKsABhZAopcKgBKq6m0zsS8G0nQ7FJkZBDexVQPdZCtG7BzWRZBCwWGDAQNv32Jm0dulyiGSKOBrZBLZA7gmnxzszGg8L95fWLMiGeV1g2x';
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
        if ($respuesta === false) {
            exit; // Salir si no se reciben datos
        }
        // Archivo llegando bien
        $respuesta = json_decode($respuesta, true);
        $tipo = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['type'];
        $id = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['id'];
        $telefonoUser = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['from'];
        $timestamp = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['timestamp'];
        $sqlCantidad = WhatsApp::where('id_wa', $id)->count();
        if ($sqlCantidad == 0) {
            if ($tipo == "image") {
                // Entrando dentro de la imagen
                $idImagen = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['image']['id'];
                $textoImagen = isset($respuesta['entry'][0]['changes'][0]['value']['messages'][0]['image']['caption']) ?
                    $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['image']['caption'] : "";
                $url = "https://graph.facebook.com/v19.0/" . $idImagen;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer EAA0cGBz1VmwBOzqlCBUHzv9mf4BsmNAqw2rLoreXSXUnxVL50mouIhdcAZAWZBLsKnqZBuRWiPcQWSE325mRwtcWbQMKsABhZAopcKgBKq6m0zsS8G0nQ7FJkZBDexVQPdZCtG7BzWRZBCwWGDAQNv32Jm0dulyiGSKOBrZBLZA7gmnxzszGg8L95fWLMiGeV1g2x'
                    ),
                ));
                $response = curl_exec($curl);
                $responseData = json_decode($response, true);
                $urlDescarga = isset($responseData['url']) ? $responseData['url'] : "";

                if ($urlDescarga) {
                    $rutaImagen = 'uploads/imagenesWpp/' . $telefonoUser . '/' . $timestamp . 'imagen.jpeg';
                    $directorio = 'uploads/imagenesWpp/' . $telefonoUser;
                    if (!is_dir($directorio)) {
                        mkdir($directorio, 0777, true);
                    }
                    file_put_contents($rutaImagen, file_get_contents($urlDescarga));
                    $mensaje = '{"ruta": "' . $rutaImagen . '", "textoImagen": "' . $textoImagen . '"}';
                }
            } else {
                $mensaje = isset($respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) ?
                    $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] : "";
            }

            // Guardar el mensaje recibido en la base de datos
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
            $this->enviarMensajeChatBot($telefonoUser, $mensaje);
        } else {
            file_put_contents("mensajeExistente.txt", $id);
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
    function convertirMinNoTilde($mensaje)
    {
        $mensaje = mb_strtolower($mensaje, 'UTF-8');
        $mensaje = str_replace(array('á', 'é', 'í', 'ó', 'ú', 'ü'), array('a', 'e', 'i', 'o', 'u', 'u'), $mensaje);
        return $mensaje;
    }
    function conversacion($mensajeRecibido)
    {
        $util = new Utils();

        $mensajenoTilde = $util->convertirMinNoTilde($mensajeRecibido);
        switch ($mensajenoTilde) {
            case $util->convertirMinNoTilde("¡Hola! Me gustaría obtener más información sobre sus servicios de viaje. ¿Podrían proporcionarme detalles sobre los destinos, paquetes disponibles y precios?"):
                return "¡Encantado! Ayúdame con su nombre y su número de cédula.";
                break;
            case $util->convertirMinNoTilde("Paul, mi número de cédula es: 1234567890"):
                return "Tras verificar en la base de datos, no se encuentra registrado.\nPara agilizar el proceso, le recomendamos registrarse.\n¿Sobre qué servicio quisieras tener información?";
                break;
            case $util->convertirMinNoTilde("No estoy seguro del destino, ¿qué paquetes me recomiendas?"):
                return  "Claro, aquí tienes tres opciones de paquetes de viaje:\nDestino: Cancún, México\nPaquete Todo Incluido: 7 días y 6 noches en una habitación doble.\nPrecio: $1500 por persona.\nServicios: Playa privada, acceso ilimitado al spa y actividades acuáticas.\n\nDestino: París, Francia\nPaquete Romántico: 5 días y 4 noches en una suite.\nPrecio: $2500 por persona.\nServicios: Tour privado por la ciudad, cena romántica en la Torre Eiffel y paseo en barco por el Sena.\n\nDestino: Tokio, Japón.\nPaquete Aventura: 10 días y 9 noches en una habitación individual.\nPrecio: $3000 por persona.\nServicios: Tour por los templos, experiencia en el distrito de Akihabara y clases de sushi.\n\nPor favor, selecciona el paquete que te interese o si necesitas más detalles sobre alguno de ellos.";
                break;
            case $util->convertirMinNoTilde("No estoy interesado por un paquete internacional, vi en la pagina un paquete en Diamond Beach"):
                return"Claro, aquí tienes los detalles del paquete Diamond Beach:\nHotel: Diamond Beach Resort & Spa.\nPrecio: $200 por habitacion.\nHabitaciones: Habitación doble con vista al mar.\nServicios: Acceso exclusivo a la playa privada, spa de lujo y actividades acuáticas.\n¿Te gustaría reservar este paquete o necesitas más información?";
                break;
            case $util->convertirMinNoTilde("Cuáles son las fechas disponibles para este paquete?"):
                return "Las fechas disponibles para el paquete Diamond Beach son del 10 al 20 de agosto, del 20 al 31 de septiembre y del 5 al 15 de octubre. ¿Te gustaría reservar?";
                break;
            case $util->convertirMinNoTilde("Me gustaría reservar para el 10 al 15 de agosto"):
                return "Perfecto, ¿cuántas personas serán?";
                break;
            case $util->convertirMinNoTilde("Dos personas"):
                return "Entendido, tu reserva para el paquete Diamond Beach del 10 al 15 de agosto para dos personas ha sido confirmada. Por favor, proporcioname tu nombre completo, un correo electronico y tu numero de telefono.";
                break;
            case $util->convertirMinNoTilde("Paul Alexander Romero Chiliguisa, paulromero90@gmail.com, 0998557785"):
                return "¡Gracias!, tenemos estas opciones de pago:\n1-Transferencia\n2-Pago con tarjeta(PayPhone)\no puedes solicitar contactarte con uno de nuestros Asesores especializados.";
                break;
            case  $util->convertirMinNoTilde("Voy a realizar mi pago con una tarjeta de credito"):
                return"Para finalizar el proceso, por favor haz clic en el siguiente enlace para proceder con el pago: https://www.payphone.app/.\n\nUna vez completado, recibirás un correo electrónico con todos los detalles de tu viaje.";
                break;
            case $util->convertirMinNoTilde("En este momento ya realice el pago"):
                return"En este momento estamos verificando el pago...\n\nTu pago se ha creditado correctamente.\n ¿Hay algo más en lo que pueda ayudarte?";
                break;
            case $util->convertirMinNoTilde("Eso seria todo por el momento, Gracias"):
                return "Gracias a ti, Buen viaje";
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
