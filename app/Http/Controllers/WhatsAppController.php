<?php

namespace App\Http\Controllers;

use App\Events\RecibirMensaje;
use App\Models\WhatsApp;
use DateTime;
use Exception;
use App\Models\Notificacion;
use Carbon\Carbon;
use CURLFile;
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
        // Guardar la solicitud en un archivo de texto para depuración (opcional)
        $fechaHoraActual = Carbon::now()->format('YmdHis');
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $extension = $archivo->extension();
            //Se podría crear otra ruta para las imagenes Enviadas
            $rutaArchivo = 'uploads/imagenesWpp/' . $numeroEnviar . '/' . $fechaHoraActual;

            file_put_contents($rutaArchivo . '.' . $extension, file_get_contents($archivo));
            $extensionesImagenes = ['jpeg', 'jpg', 'png', 'gif'];
            $extensionesArchivos = ['.pdf', '.doc', '.docx', '.xlsx', '.xls', '.xml', '.svg'];

            if (in_array($extension, $extensionesImagenes)) {
                return $this->enviarMensajeMult($numeroEnviar, $mensaje,  "image", $rutaArchivo . '.' . $extension);
            } elseif (in_array($extension, $extensionesArchivos)) {
                return $this->enviarMensajeMult($numeroEnviar, $mensaje, "doc", $rutaArchivo . '.' . $extension);
            }
        }

        // Llamar a la función para enviar el mensaje
        return $this->enviarMensaje($numeroEnviar, $mensaje, "texto");
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
            if ($mensaje == null) {
                return;
            }
        }


        if (gettype($mensaje) != 'array') {
            $url = $this->convertirTextoAudio($mensaje, $numeroEnviar);
            $this->enviarMensajeMult($numeroEnviar, $mensaje, 'audio', getenv('URL_RECURSOS') . '/' . $url);
        } else {
            foreach ($mensaje as $elem) {
                $this->enviarMensaje($numeroEnviar, $elem, "texto");
            }
        }
    }

    function enviarMensajeMult($numeroEnviar, $mensaje, $tipo, $url)
    {

        if (strncmp($numeroEnviar, '0', strlen('0')) === 0) {
            $numeroEnviar = '593' . substr($numeroEnviar, 1);
        }
        if ($tipo == "image") {
            $urlRequest = 'https://graph.facebook.com/v' . getenv('WPP_MULTIVERSION') . '/' . getenv('WPP_ID') . '/messages';
            $url = getenv('URL_RECURSOS') . '/' . $url;
            //$url = "http://127.0.0.1:8000/" . $url;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $urlRequest,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "messaging_product": "whatsapp",
                "recipient_type": "individual",
                "to": "' . $numeroEnviar . '",
                "type": "image",
                "image": {
                    "link": "' . $url . '",
                    "caption": "' . $mensaje . '"
                }
            }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . getenv("WPP_TOKEN"),
                    'Cookie: ps_l=0; ps_n=0'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            $mensaje = '{"ruta": "' . $url . '", "textoImagen": "' . $mensaje . '"}';
            //echo $response;
        }
        if ($tipo == "doc") {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v' . getenv('WPP_MULTIVERSION') . '/' . getenv('WPP_ID') . '/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                "messaging_product": "whatsapp",
                "recipient_type": "individual",
                "to": "' . $numeroEnviar . '",
                "type": "document",
                "document": {
                    "link": "' . $url . '"
                }
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . getenv("WPP_TOKEN"),
                    'Cookie: ps_l=0; ps_n=0'
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            //echo $response;

        }
        if ($tipo == "audio") {

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://graph.facebook.com/v' . getenv('WPP_MULTIVERSION') . '/' . getenv('WPP_ID') . '/messages',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
                    "messaging_product": "whatsapp",
                    "recipient_type": "individual",
                    "to": "' . $numeroEnviar . '",
                    "type": "audio",
                    "audio": {
                        "link": "' . $url . '"
                    }
                }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . getenv('WPP_TOKEN'),
                    'Cookie: ps_l=0; ps_n=0'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            return "Audio";
        }

        $idMensajeEnviar = json_decode($response, true)['messages'][0]['id'];
        $whatsApp = new WhatsApp();

        $whatsApp->mensaje_enviado = $mensaje;
        $whatsApp->id_wa = $idMensajeEnviar;
        $whatsApp->telefono_wa = getenv('WPP_NUM');
        $whatsApp->id_numCliente = $numeroEnviar;
        $whatsApp->fecha_hora = new DateTime('now');
        $whatsApp->visto = true;
        $whatsApp->save();
        curl_close($curl);
        return json_encode($whatsApp);
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
        $whatsApp->telefono_wa = getenv('WPP_NUM');
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
            $whatsApp = new WhatsApp();
            if ($tipo == "audio") {
                $audio = $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['audio'];
                $idAudio = $audio['id'];
                $responseAudio = $this->obtenerMultimedia($idAudio);
                $directorio = 'uploads/audiosWpp/' . $telefonoUser;
                $rutaAudio = $directorio . '/' . $timestamp . 'audio.wav';
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0777, true);
                }
                file_put_contents($rutaAudio, $responseAudio);
                //Enviar el mensaje del chatbot
                $url = 'http://localhost:5000/audio';
                $ch = curl_init($url);
                $cfile = new CURLFile(realpath($rutaAudio));
                $data = array('audio_file' => $cfile);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch); // Variable que contiene lo que dice el usuario
                curl_close($ch);
                $mensaje = $this->conversacion($response);
                $rutaAudio =  $this->convertirTextoAudio($mensaje, $telefonoUser);
                $whatsApp = $this->guardarMensaje($timestamp, $mensaje, $id, $telefonoUser);
                //$this->enviarMensajeMult($telefonoUser, $mensaje, 'audio', getenv('URL_RECURSOS') . '/' . $rutaAudio); //Envia el mismo mensaje de vuelta
                $this->enviarMensaje($telefonoUser, $mensaje);

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

                $whatsApp = $this->guardarMensaje($timestamp, $mensaje, $id, $telefonoUser);
                $this->enviarMensajeChatBot($telefonoUser, "imagen");
            } else {
                $mensaje = isset($respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']) ?
                    $respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'] : "";

                $whatsApp = $this->guardarMensaje($timestamp, $mensaje, $id, $telefonoUser);
                $this->enviarMensajeChatBot($telefonoUser, $mensaje);
            }
            // Evento de recibir mensaje

            $event = new RecibirMensaje(
                $telefonoUser,
                $mensaje,
                $whatsApp->fecha_hora->toArray() // Convertir el objeto DateTime a un array
            );
            event($event);
            $cadenaNotificacion = 'Mensaje de : ' . $telefonoUser;
            $this->crearNotificacion($cadenaNotificacion, $mensaje);
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
    function crearNotificacion($mensaje, $comentario)
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
    function guardarMensaje($timestamp, $mensaje, $id, $telefonoUser)
    {
        $whatsApp = new WhatsApp();
        $whatsApp->timestamp_wa = $timestamp;
        $whatsApp->mensaje_enviado  = $mensaje;
        $whatsApp->id_wa = $id;
        $whatsApp->visto = false;
        $whatsApp->telefono_wa = $telefonoUser;
        $whatsApp->id_numCliente = $telefonoUser;
        $whatsApp->fecha_hora = new DateTime('now');
        $whatsApp->save();
        return $whatsApp;
    }

    function convertirTextoAudio($texto, $numeroEnviar)
    {
        $data = array("texto" => $texto); // Aquí se pasa el texto como un parámetro
        $url = 'http://127.0.0.1:5000/audioToText';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);
        $fechaHoraActual = Carbon::now()->format('YmdHis');
        $ruta = 'uploads/audiosWpp/' . $numeroEnviar . '/' . $fechaHoraActual . '.mp3';
        // Guardar el archivo MP3 en el servidor
        file_put_contents($ruta, $response);
        return $ruta;
    }

    function conversacion($mensajeRecibido)
    {

        $util = new Utils();

        $mensajenoTilde = $util->convertirMinNoTilde($mensajeRecibido);
        switch ($mensajenoTilde) {
             case $util->convertirMinNoTilde("hola"):
                 return "Hola, buen dia";
                break;
             case $util->convertirMinNoTilde("Buenos días vi una publicación sobre México y estoy interesada"):
                 return "Para poder ayudarte necesitamos saber la siguiente información\nNombre:\nCorreo electrónico:\nDestino:\nFecha tentativa:\nCuantas personas viajan:\nEdades:\nSalida de Quito o Guayaquil:";
                 break;
             case $util->convertirMinNoTilde("Nombre Antonella Garcia
Correo electrónico antonella.garciacamacho@gmail.com
Destino Cartagena
Fecha tentativa 25 de mayo del 2024
Cuantas personas viajan 2
Edades 24
Salida de Quito o Guayaquil Quito"):
                 return null;
                 break;
            case $util->convertirMinNoTilde("audio 1"):
                return "Estimada Antonella García,\n¡Gracias por ponerte en contacto con nosotros para planificar tu viaje a México ! Nos emociona mucho ayudarte a organizar una experiencia inolvidable.";
                break;
            case $util->convertirMinNoTilde("Gracias"):
                return "MÉXICO Y CANCÚN!\n 6 NOCHES\nTicket aéreo Quito o Guayaquil / México - Cancún / Quito o Guayaquil VÍA COPA AIRLINES\nINCLUYE:\nEN CD. DE MÉXICO:\n Traslado aeropuerto / hotel / aeropuerto en servicio compartido\n 3 noches de alojamiento en HOTEL REGENTE O SIMILAR\n Desayunos diarios incluidos\n Cortesías:\n- Almuerzo en Teotihuacán\nEN CANCÚN:\n Traslado aeropuerto / hotel / aeropuerto en servicio compartido\n 3 noches de alojamiento en CANCUN BAY RESORT\n Plan alimenticio todo incluido\n$880";
                break;
            case $util->convertirMinNoTilde("Que actividades incluye"):
                return null;
                break;
            case $util->convertirMinNoTilde("audio 2"):
               return "CD. MÉXICO ACTIVIDADES\n Visita panorámica a Plaza Garibaldi\n Basílica de Guadalupe y Pirámides de Teotihuacán";
               break;
            case $util->convertirMinNoTilde("Muchas gracias"):
                return null;
                break;
                case $util->convertirMinNoTilde("Por favor puede decirme que no incluye el paquete"):
                    return "No incluye\n•	Ticket aéreo interno CUN – \n•	Actividades no detalladas en el programa.\n•	Comidas y bebidas no indicadas en el programa.\n•	Extras personales en hoteles y restaurantes.\n•	Propinas.\n•	Tarjeta de asistencia médica.";
                    break;
            case $util->convertirMinNoTilde("Muchas gracias por la informacion"):

                     return "Para mantener esta reservación en firme se requiere el pago del abono de $100, NO REEMBOLSABLE en el caso de pasajeros individuales o grupos. Aplica hasta 31 días antes del viaje para individuales y 46 días para grupos.\n•	El pago total de una reservación deberá ser realizada hasta 30 días antes de la salida.\n•	Si una reservación ingresa 30 días antes de la salida, el pago total deberá estar realizado en 24 horas luego de haber sido realizada la misma.\n•	Reservaciones que no tengan pago SE CANCELARÁN a las 24 HORAS.\n•	En el caso de grupos de pasajeros el pago total se debe realizar 45 días antes de la salida.\n•	Penalidad por cambio de nombre: USD $150 hasta 10 días antes de la salida en vuelo chárter.\n•	No se permite cambios de fecha o destino.\n•	Al momento de la facturación usted acepta estar de acuerdo con los servicios detallados y está de acuerdo con las penalidades descritas en esta liquidación de servicios sin excepción alguna.";
                     break;
                 case $util->convertirMinNoTilde("Listo cuáles serían los métodos de pago"):
                     return "Claro Para completar tu reserva, te ofrecemos varias opciones de pago. Puedes realizar una transferencia bancaria, pagar con tarjeta de crédito o utilizar otras plataformas de pago en línea. Por favor, avísanos cuál prefieres y te proporcionaremos los detalles necesarios para proceder.";
                     break;
            case $util->convertirMinNoTilde("Deseo pagar con tarjeta de crédito"):
                     return "Claro para procesar el pago con tarjeta de crédito, por favor haz clic en el siguiente link: https://www.payphone.app/.";
                     break;
            case $util->convertirMinNoTilde("Y si deseo pagar en efectivo?"):
                     return "No hay problema. Para realizar el pago, por favor acérquese a nuestras oficinas ubicadas en el centro comercial Galería Plaza, Local N7.  ¿A qué hora te gustaría agendar una cita? Estamos disponibles de lunes a viernes de 9 am a 6 pm . Por favor, avísenos su preferencia para coordinar la cita. ¡Gracias!";
                 break;
             case $util->convertirMinNoTilde("No voy a poder acercarme :( deseo que me mande su número de cuenta mejor, para hacerle el abono"):
                        return "Con gusto, los datos de la cuenta bancaria son:\nCuenta Corriente Banco Guayaquil\nCuenta N°: 0041291060\nNombre: Trivai S.A\nRUC: 1793198413001";
                     break;
            case $util->convertirMinNoTilde("Listo"):
                 $mensaje6 = "¡Muchas gracias por realizar tu pago! En este momento procedemos a confirmar tu reserva y asegurarnos de que todo esté en orden para tu viaje. Si tienes alguna pregunta o necesitas asistencia durante el proceso de pago, no dudes en contactarnos. Puedes comunicarte con nuestro equipo de soporte al número 099926280 que te acompañara en tu viaje.";
                 $mensaje7 = "Estimado/a Antonella García\n\n Queremos confirmar que hemos recibido su pago y que su reserva está ahora completa. Todos los detalles de su viaje han sido registrados y estamos emocionados de asistirlo/a en cada paso del camino.\nSi tiene alguna pregunta adicional o necesita más información, no dude en ponerse en contacto con nosotros. Estamos aquí para ayudarlo/a.\n¡Gracias por confiar en nosotros para su viaje y esperamos que tenga una experiencia memorable!";
                    $retorno = [$mensaje6, $mensaje7];
                 return $retorno;
                 break;
             case $util->convertirMinNoTilde("Gracias, cualquier cosa le informo"):
                 return "Estamos para antenderte, si deseas otra cosa no dudes en informarme";
                 break;
                 case $util->convertirMinNoTilde("Muchisimas gracias"):
                    return null;
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
