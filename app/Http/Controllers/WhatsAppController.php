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
            if($mensaje == null){
                return;
            }
        }

        if (gettype($mensaje) != 'array') {
            $this->enviarMensaje($numeroEnviar, $mensaje);
        } else {
            foreach ($mensaje as $elem) {
                $this->enviarMensaje($numeroEnviar, $elem);
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
            $whatsApp = new WhatsApp();
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
                //Enviar el mensaje del chatbot

                $whatsApp = $this->guardarMensaje($timestamp, $mensaje, $id, $telefonoUser);
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

    function conversacion($mensajeRecibido)
    {

        $util = new Utils();

        $mensajenoTilde = $util->convertirMinNoTilde($mensajeRecibido);
        switch ($mensajenoTilde) {
            case $util->convertirMinNoTilde("Buenos dias"):
                return null;
                break;
            case $util->convertirMinNoTilde("Ayúdame con información"):
                return "¡Encantado! ¿Con quién tengo el gusto?";
                break;
            case $util->convertirMinNoTilde("Me llamo Antonella"):
                return "Mucho gusto, Antonella. ¿En qué puedo ayudarte?";
                break;
            case $util->convertirMinNoTilde("Me podría ayudar con más información sobre promociones en Cartagena,Orlando y Miami"):
                return "Buenos días ☺️☀️para poder ayudarte necesitamos saber la siguiente información\nNombre:\nCorreo electrónico:\nDestino:\nFecha tentativa:\nCuantas personas viajan:\nEdades:\nSalida de Quito o Guayaquil:";
                break;
            case $util->convertirMinNoTilde("Nombre Antonella Garcia
            Correo electrónico antonella.garciacamacho@gmail.com
            Destino Cartagena
            Fecha tentativa 25 de mayo del 2024
            Cuantas personas viajan 2
            Edades 24 y 25
            Salida de Quito o Guayaquil Quito"):
                $mesnaje0 = "Estimada Antonella García\n¡Gracias por ponerte en contacto con nosotros para planificar tu viaje a Cartagena! Nos emociona mucho ayudarte a organizar una experiencia inolvidable.";
                $mensaje1 = "Recibimos su pedido de cotización a Cartagena para dos personas para la fecha del 25 de mayo me puede confirmar.";
                $retorno = [$mesnaje0, $mensaje1];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Si porfavor ☺️"):
                $mensaje2 = "CARTAGENA 3 NOCHES Ticket aéreo Guayaquil o Quito / Cartagena / Quito o Guayaquil Vía avianca\n\nINCLUYE:\n\n•	Traslado aeropuerto / hotel / aeropuerto en servicio compartido\n•	03 noches de alojamiento en HOTEL ZALMEDINA\n•	Desayunos diarios\n•	Cortesía:\n-	City tour + Visita al Castillo de San Felipe con entrada.\n-	Chip + bolsa de café por habitación\n•	Impuestos hoteleros y aéreos\n$447";
                $mensaje3 = "CARTAGENA 3 NOCHES TODO INLUIDO Ticket aéreo Guayaquil o Quito / Cartagena / Quito o Guayaquil Vía avianca\n\nINCLUYE:\n\n•	Traslado aeropuerto / hotel / aeropuerto en servicio compartido\n•	03 noches de alojamiento en HOTEL CARTAGENA PLAZA\n•	Desayuno, almuerzo y cena tipo buffet\n•	Snacks y bar abierto\n•	  Piscina panorámica\n•	  Bar & discoteca\n•	Noches temáticas y shows de baile\n•	Kids & teen clubs\n•	Wifi gratuito en las instalaciones\n•	CORTESÍAS:\n-	City tour + Castillo de San Felipe con entrada\n-  Chip celular y bolsa de café por habitación\n$565";
                $mensaje4 = "¡CARTAGENA! ALL INCLUSIVE 04 NOCHES Ticket aéreo QUITO - GUAYAQUIL / CARTAGENA / GUAYAQUIL - QUITO\n\nINCLUYE:\n\n•	Traslado aeropuerto / hotel / aeropuerto en servicio compartido\n• 	04 noches de alojamiento en HOTEL CARTAGENA PLAZA\n•	Desayuno, almuerzo y cena tipo buffet\n•	Snacks y bar abierto\n•	Piscina panorámica\n•	Bar & discoteca\n•	Noches temáticas y shows de baile\n•	Kids & teen clubs\n•	Wifi gratuito en las instalaciones\n$829";
                $mensaje5 = "Te proporcionamos tres opciones de cotización para tu viaje a Cartagena.\nCon gusto ☺️ por favor, revisa la cotización adjunta para más detalles sobre vuelos, alojamiento y servicios incluidos. Si estás de acuerdo y deseas proceder con la reserva, o necesitas ajustes en el itinerario háznoslo saber. Estamos aquí para ayudarte en todo el proceso de planificación.";
                $retorno = [$mensaje2, $mensaje3, $mensaje4, $mensaje5];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Muchas gracias ya las reviso ☺️"):
                return null;
                break;
            case $util->convertirMinNoTilde("Me intereso cartagena 3 noches todo incluido porfavor"):
                return null;
                break;
            case $util->convertirMinNoTilde("Cómo puedo proceder con la reserva?"):
                return "¡Por supuesto! Para proceder con la reserva, necesitaremos algunos detalles adicionales de tu parte. Por favor, proporciona los siguientes datos:\nNombres completos de los viajeros:\nNúmeros de pasaporte:\nDirección de correo electrónico y número de teléfono de contacto:\nCualquier preferencia especial o requisito dietético que necesitemos tener en cuenta durante tu estadía.\nUna vez que recibamos esta información, nuestro equipo se pondrá en contacto contigo para confirmar la reserva y proceder con los detalles de pago.\nSi tienes alguna pregunta o necesitas ayuda adicional, no dudes en hacérnoslo saber.";
                break;
            case $util->convertirMinNoTilde("Antonella Garcia
1764309854
Antonella.garciacamacho@gmail.con
Vegetariana
Adrián Ruiz
1845876324
Adriag2345@gmail.com
Ninguna"):
                return "¡Perfecto, Antonella y Adrián! Hemos registrado sus detalles y preferencias. Nuestro equipo se pondrá en contacto contigo en breve para confirmar la reserva y proporcionarte todos los detalles necesarios para tu viaje a Cartagena.";
                break;
            case $util->convertirMinNoTilde("¿Cuáles son las fechas disponibles para este paquete?"):
                return "Las fechas disponibles para el paquete Grand Oasis Cancún, México son: del 10 al 20 de agosto, del 20 al 31 de septiembre y del 5 al 15 de octubre. ¿Te gustaría reservar?";
                break;
            case $util->convertirMinNoTilde("Gracias"):
                return "•   Para mantener esta reservación en firme se requiere el pago del abono de $100, NO REEMBOLSABLE en el caso de pasajeros individuales o grupos. Aplica hasta 31 días antes del viaje para individuales y 46 días para grupos.\n•	El pago total de una reservación deberá ser realizada hasta 30 días antes de la salida.\n•	Si una reservación ingresa 30 días antes de la salida, el pago total deberá estar realizado en 24 horas luego de haber sido realizada la misma.\n•	Reservaciones que no tengan pago SE CANCELARÁN a las 24 HORAS.\n•	En el caso de grupos de pasajeros el pago total se debe realizar 45 días antes de la salida.\n•	Penalidad por cambio de nombre: USD $150 hasta 10 días antes de la salida en vuelo chárter.\n•	No se permite cambios de fecha o destino.\n•	Al momento de la facturación usted acepta estar de acuerdo con los servicios detallados y está de acuerdo con las penalidades descritas en esta liquidación de servicios sin excepción alguna.";
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
                //mandas la foto
            case $util->convertirMinNoTilde("Listo"):
                $mensaje6 = "¡Muchas gracias por realizar tu pago! En este momento procedemos a confirmar tu reserva y asegurarnos de que todo esté en orden para tu viaje. Si tienes alguna pregunta o necesitas asistencia durante el proceso de pago, no dudes en contactarnos. Puedes comunicarte con nuestro equipo de soporte al número 099926280 que te acompañara en tu viaje.";
                $mensaje7 = "Estimado/a Antonella García y Adrian Ruiz\n\n Queremos confirmar que hemos recibido su pago y que su reserva está ahora completa. Todos los detalles de su viaje han sido registrados y estamos emocionados de asistirlo/a en cada paso del camino.\nSi tiene alguna pregunta adicional o necesita más información, no dude en ponerse en contacto con nosotros. Estamos aquí para ayudarlo/a.\n¡Gracias por confiar en nosotros para su viaje y esperamos que tenga una experiencia memorable!";
                $retorno = [$mensaje6, $mensaje7];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Muchísimas gracias ☺️"):
                return null;
                break;
            case $util->convertirMinNoTilde("También quería preguntarle si tiene paquetes a Orlando y Miami"):
                $mensaje8 = "ORLANDO FANTASTICO\n05 NOCHES\nGVMCO24-002\n\nINCLUYE:\n\nTicket aéreo UIO/GYE – BOG – MCO – BOG – GYE/UIO vía AVIANCA AIRLINES\n•	Traslado aeropuerto / hotel / aeropuerto en servicio compartido\n•	05 noches de alojamiento en HOTEL ROSEN INN LAKE BUENAVISTA\n•	Desayunos Buffet diarios (07:00 a 9:00)\n•	01 día de admisión a AQUATICA\n•	01 día de admisión a SEAWORLD\n•	01 día de admisión al BUSCH GARDENS\n•	CORTESÍA POR HABITACIÓN:\n-	01 almohada de viaje\n-	Tour de compras PREMIUM OUTLET\n$1441";
                $mensaje9 = "¡ORLANDO FULL!\n 05 NOCHES\nGVMCO01\n\n Ticket aéreo Quito o Guayaquil/ Orlando / Quito o Guayaquil vía COPA AIRLINES\n\nINCLUYE:\n•	Traslado aeropuerto / hotel / aeropuerto en servicio compartido\n•	05 noches de alojamiento en HOTEL ROSEN INN LAKE BUENAVISTA\n•	Desayunos\n•	01 día de visita a Universal Studios park-to-park con admisión y traslados ida y vuelta\n•	01 día de visita a Isla de la aventura park-to-park, con admisión y traslados ida y vuelta\n•	01 día de tour de compras a Premium Outlet International Drive, con traslados ida y vuelta\n•	01 día libre\n•	Impuestos hoteleros y aéreos\n$1528";
                $retorno = [$mensaje8, $mensaje9];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Gracias porfavor me puede enviar para Miami"):
                $mensaje10 = "¡MIAMI SALE!\n 03 NOCHES\nTicket aéreo Quito o Guayaquil / Miami / Quito o Guayaquil VÍA avianca\INCLUYE:\n•	Traslado aeropuerto / hotel en servicio shuttle*\n•	03 noches de alojamiento en HOTEL CLARION INN & SUITES MIAMI\n•	Tour de compras a Dolphin Mall\n•	Impuestos aéreos y hoteleros\n$627";
                $mensaje11 = "¡MIAMI SALE!\n03 NOCHES\nTicket aéreo Quito o Guayaquil / Miami / Quito o Guayaquil VÍA avianca\nINCLUYE:\n•	Traslado aeropuerto / hotel en servicio shuttle*\n•	03 noches de alojamiento en HOTEL CLARION INN & SUITES MIAMI\n•	Tour de compras a Dolphin Mall\n•	Impuestos aéreos y hoteleros\n$627";
                $retorno = [$mensaje10, $mensaje11];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Muchas gracias los voy a revisar para unas próximas vacaciones"):
                return "Con gusto Antonella. Por favor ten en cuenta que los precios están sujetos a disponibilidad y podrían variar hasta la confirmación final de la reserva, si deseas proceder con ella, háznoslo saber y estaremos encantados de gestionarlo todo para ti.";
                break;
            case $util->convertirMinNoTilde("Lo entiendo, Gracias"):
                return null;
                break;
            case $util->convertirMinNoTilde("Señorita de igual manera mi madre desea ir a Europa porfavor me puede mandar las opciones"):
                return "Estimada Antonella para poder ayudarle necesitamos saber la siguiente información\n Nombre:\nCorreo electrónico:\nDestino:\nFecha tentativa:\nCuantas personas viajan:\nEdades:\nSalida de Quito o Guayaquil:";
                break;
            case $util->convertirMinNoTilde("Nombre: Marisol Camacho
Correo: Marylu3020@gmail.com
Destino: Europa
Fecha tentativa: 15 de Junio
Cuántas personas: 1
Edad: 60
Salida: Quito"):
                return "Recibimos su pedido de cotización a Europa para una persona para la fecha del 15 de junio  me puede confirmar.";
                break;
            case $util->convertirMinNoTilde("Si porfavor"):
                $mensaje12 = "Antonella, le adjunto la cotización del Eurotrip dorada para las fechas estipuladas";
                $mensaje13 = "Programa Incluye\nTour 16 días - 15 noches\n* Boleto aéreo QUITO - MADRID- QUITO con Iberia\n* Traslado aeropuerto – hotel – aeropuerto\n* Alojamiento en categoría Turista\n* Desayuno diario Buffet\n* Guía acompañante durante todo el viaje\n* Guías locales en español en las visitas indicadas en el itinerario\n* SEGURO DE VIAJE (APLICA PARA VISADO SCHENGEN)\n* Impuestos aéreos\n* Impuestos Ecuatorianos\n$3422";
                $retorno = [$mensaje12, $mensaje13];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Qué ciudades se visitan?"):
                return "Madrid,Paris,Venecia,Florencia,Roma,Costa Azul,Barcelona,Zaragoza";
                break;
            case $util->convertirMinNoTilde("Muchas gracias ☺️ ya le confirmos"):
                return null;
                break;
            case $util->convertirMinNoTilde("Porfavor señorita pueden solo enviarme la cotización de un vuelo a Madrid"):
                return "¡Por supuesto! Antonella para poder ayudarte necesitamos la siguiente información para enviarte las opciones de vuelo disponibles.\nFecha de salida:\n Destino:\n Número de pasajeros:";
                break;
            case $util->convertirMinNoTilde("15 de junio - 15 de julio Madrid 1"):
                $mensaje12 = "Encontramos dos opciones buenas de viaje para ti en las fechas estipuladas";
                $mensaje13 = "https://www.avianca.com/es/booking/select/?departure1=2024-06-15&departure2=2024-07-15&platform=WEBB2C&origin1=UIO&destination1=MAD&adt1=1&chd1=0&inf1=0&posCode=EC&origin2=MAD&destination2=UIO&adt2=1&chd2=0&inf2=0&currency=USD&CorporateCode=&Device=Web";
                $mensaje14 = "https://www.latamairlines.com/ec/es/seleccion-asientos?id=LA4625048IYJY";
                $retorno = [$mensaje12, $mensaje13, $mensaje14];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Disculpe talvez un hotel en galapagos todo incluido ya que quiero viajar a fin de este mes"):
                $mensaje12 = "Si Antonella contamos con el hotel Torruga Vay $321 adultos y niños $290 por persona sin ticket aéreo ya que este depende de la temporada que usted viaje.";
                $mensaje13 = "Incluye\n-Transfer in - Visita parte alta\n-Playa Tortuga Bay\n-Visita estación Charles Darwin\n-Visita Playa de los Alemanes y Grietas\n-Tranfer out\nIncluye alimentación completa";
                $retorno = [$mensaje12, $mensaje13];
                return $retorno;
                break;
            case $util->convertirMinNoTilde("Muchas gracias voy a conversar con mi esposo"):
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
