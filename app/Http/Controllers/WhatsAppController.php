<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;
use App\Models\WhatsApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class WhatsAppController extends Controller
{
    public function index(){
        $mensajes = WhatsApp::all(); // Por ejemplo, aquí obtienes todos los mensajes de tu modelo
        return view('dashboard', ['mensajes' => $mensajes]);

    }
    public function envia(Request $request)
    {

        $enviado = $request->input('enviado');
        $telefonoCliente = $request->input('telefonoCliente');

        //token que nos da facebook
        $token ='EAA0cGBz1VmwBO2PfnWb1Ih05lj3PagvoDGM0JIZCMqKXmEZCnFd7Ntdws2d2IOfGpAYAWQAxO5F5tzrZBfXQdQPdQXzfZCsoqZC1RahWBZCQFm7clpzm38bpc50yKu4Oy9ZCK9egwTcasNdwqHZC5XdnGkBZAAzgK2H4kzWnz3Yqgg6l086YZCR5jHXoGtFgbiMoZCZCsl7KT7MzepVuGbjIMt0ZD';
        // nuestro telefono
        $telefonoID = '224013397467233';
        //url a donde se manda el mensaje
        $url = 'https://graph.facebook.com/v15.0/' . $telefonoID . '/messages';

         //CONFIGURACION DEL MENSAJE
        $mensaje = ''
                . '{'
                . '"messaging_product": "whatsapp", '
                . '"recipient_type": "individual",'
                . '"to": "' . $telefonoCliente . '", '
                . '"type": "text", '
                . '"text": '
                . '{'
                . '     "body":"' . $enviado . '",'
                . '     "preview_url": true, '
                . '} '
                . '}';

        //declaramos las cabeceras
        $header = array("Authorization: Bearer ".$token, "Content-Type: application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $mensaje);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($curl),true);

        print_r($response);

        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);


        return redirect()->route('dashboard');

    }
    public function webhook(){
        //TOQUEN QUE QUERRAMOS PONER
        $token = 'TokenValidacion';
        //RETO QUE RECIBIREMOS DE FACEBOOK
        $hub_challenge = isset($_GET['hub_challenge']) ? $_GET['hub_challenge'] : '';
        //TOQUEN DE VERIFICACION QUE RECIBIREMOS DE FACEBOOK
        $hub_verify_token = isset($_GET['hub_verify_token']) ? $_GET['hub_verify_token'] : '';
        //SI EL TOKEN QUE GENERAMOS ES EL MISMO QUE NOS ENVIA FACEBOOK RETORNAMOS EL RETO PARA VALIDAR QUE SOMOS NOSOTROS
        if ($token === $hub_verify_token) {
            echo $hub_challenge;
            exit;
        }
      }
      /*
      * RECEPCION DE MENSAJES
      */
    public function recibe(){
        //LEEMOS LOS DATOS ENVIADOS POR WHATSAPP
        $respuesta = file_get_contents("php://input");
        //echo file_put_contents("text.txt", "Hola");
        //SI NO HAY DATOS NOS SALIMOS
        if($respuesta==null){
          exit;
        }
        //CONVERTIMOS EL JSON EN ARRAY DE PHP
        $respuesta = json_decode($respuesta, true);
        //EXTRAEMOS EL TELEFONO DEL ARRAY
        $mensaje="Telefono:".$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['from']."\n";
        //EXTRAEMOS EL MENSAJE DEL ARRAY
        $mensaje.="Mensaje:".$respuesta['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
        //GUARDAMOS EL MENSAJE Y LA RESPUESTA EN EL ARCHIVO text.txt
        file_put_contents("text.txt", $mensaje);
    }

    public function notificacionMensaje(){
        $mensajes = WhatsApp::all();

        $mensajesFiltrados = $mensajes->filter(function ($mensaje) {
            return $mensaje->telefono_envia !== $mensaje->telefono_recibe;
        });
        return view('dashboard', compact('mensajes')); // Pasa los mensajes a la vista 'dashboard'
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
