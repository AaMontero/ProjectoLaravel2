<?php

namespace App\Http\Controllers;

use App\Models\WhatsApp;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class WhatsAppController extends Controller
{


    public function index()
    {
        $mensajes = WhatsApp::all(); // Por ejemplo, aquí obtienes todos los mensajes de tu modelo
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
                'Authorization: Bearer EAA0cGBz1VmwBOyPIAWVggBulUedn4Bxeq1VzHNHxZBW25Hh11bfuZBOz704eaq7MP06LgoRzKSuCaJFw6ZCWsA8pb6dqrqC3yaruT2hhopjoCF7mTO4nMyNG7XyQ5OcLPSujzHzAqcPksvlQLk3igDh2FnXyZA803qaiIdzJO1x09euof8c2XYBZBy9VNfcr3haXmD4HOVJByZBIlYLTQZD'
            ),
        ));


        $response = curl_exec($curl);
        $whatsApp = new WhatsApp();
        $whatsApp->mensaje_enviado  = $mensaje;
        $whatsApp->id_wa = "asdasdasd";
        $whatsApp->telefono_wa = "593987411818";
        $whatsApp->id_numCliente = $numeroEnviar;
        $whatsApp->fecha_hora = new DateTime('now');
        $whatsApp->save();
        curl_close($curl);
        echo $response;
    }
    public function envia(Request $request)
    {

        $enviado = "Texto";
        //$telefonoCliente = $request->input('telefonoCliente');
        $telefonoCliente = '593979345051';
        //token que nos da facebook
        $token = 'EAA0cGBz1VmwBO7JfpWGn8eR74d51Kk36ahQtEDTUo8nMow9Hytfmvpe2jCzWkQQrA1JzzDlN6aXen9myp9m75dYcLh7scSXdayhdmTwbjhMJzMb9UrgFqfFNzhxDSZCdPNtTSCq5y4gGS2Woi1gPHudZCp5jOivjJjAuiVY8qTwH50lQJIp6heMGp3yJpb';
        // nuestro telefono
        $telefonoID = '258780720641927';
        //url a donde se manda el mensaje
        $url = 'https://graph.facebook.com/v18.0/258780720641927/messages';

        //CONFIGURACION DEL MENSAJE
        $mensaje = ''
            . '{'
            . '"messaging_product": "whatsapp", '
            . '"recipient_type": "individual",'
            . '"to": "' . $telefonoCliente . '", '
            . '"type": "text", '
            . '"text": '
            . '{'
            . '     "body":  "' . $enviado . '",'
            . '     "preview_url": true, '
            . '} '
            . '}';

        //declaramos las cabeceras
        $header = array("Authorization: Bearer " . $token, "Content-Type: application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        //file_put_contents("response.txt", $response);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $mensajes = WhatsApp::all(); // Por ejemplo, aquí obtienes todos los mensajes de tu modelo
        return view('chat.chat', ['mensajes' => $mensajes]);
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
        $whatsApp->telefono_wa = $telefonoUser;
        $whatsApp->id_numCliente = $telefonoUser;
        $whatsApp->fecha_hora = new DateTime('now');
        $whatsApp->save();
    }

    public function notificacionMensaje()
    {
        $mensajes = WhatsApp::all();
        return view('chat.chat', compact('mensajes')); // Pasa los mensajes a la vista 'dashboard'
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
