<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Http;


class WebhookController extends Controller
{
    public function sendMessage()
    {
        try {
            $token = 'EAA0cGBz1VmwBO3iS2LpEZAAR5ECVjzIV2wQFOAlNB987Xm2WAZBtPtyTgushApK8ojgq1bpsIUWUxZAqLedN7U21DLZCGTpTANfxerBNhuZB4vB8492ZC77Aw56DwZCey08x1TaXJ0RjdBBvIZBD79BGXaiSlyaTrl2tBYqizijG5G7J5kDDPRV90arZC36nZCFzMT';
            $phoneId = '258780720641927';
            $version = '18.0';
            $payload = [
                'messaging_product' => 'whatsapp',
                'to' => '593998557785',
                'type' => 'template',
                'template' => [
                    'name' => 'hello_world', // Asegúrate de que el nombre del template sea correcto
                    'language' => [
                        'code' => 'en_US', // Elimina el espacio extra en "code "
                    ]
                ]
            ];

            $message = Http::withToken($token)->post('https://graph.facebook.com/' . $version . '/' . $phoneId . '/messages', $payload)
                ->throw()
                ->json();

            return response()->json([
                'success' => true,
                'data' => $message,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // public function sendMenssage()
    // {     try{
    //         $token ='EAA0cGBz1VmwBO3iS2LpEZAAR5ECVjzIV2wQFOAlNB987Xm2WAZBtPtyTgushApK8ojgq1bpsIUWUxZAqLedN7U21DLZCGTpTANfxerBNhuZB4vB8492ZC77Aw56DwZCey08x1TaXJ0RjdBBvIZBD79BGXaiSlyaTrl2tBYqizijG5G7J5kDDPRV90arZC36nZCFzMT';
    //         $phoneId = '258780720641927';
    //         $version = '18.0';
    //         $pyload = [
    //             'messaging_product' => 'whatsapp',
    //             'to' => '',
    //             'type' =>'template',
    //             "template"=>[
    //                 "name"=> "hello_word",
    //                 "language"=> [
    //                     "code "=> "en_US"
    //                 ]
    //             ]
    //         ];

    //         $message = Http::withToken($token)->post('https://graph.facebook.com/' . $version . '/' . $phoneId . '/messages',$pyload)
    //         ->throw()->json();

    //         return response()->json([
    //             'succes' => true,
    //             'data' => $message,
    //         ], 200);
    //     }catch (Exception $e){
    //         return response()->json([
    //         'succes' => false,
    //         'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    // // public function webhook(Request $request)
    // // {

    // //     try {
    // //         $verifyToken = 'TokenVerificacion';
    // //         $query = $request->query();
    // //         $mode = $query['hub_mode'];
    // //         $token = $query['hub_verify_token'];
    // //         $challenge = $query['hub_challenge'];
    // //         if ($mode && $token) {
    // //             if ($mode === 'subscribe' && $token == $verifyToken) {
    // //                 return response($challenge, 200)->header('Content-Type', 'text/plain');
    // //             }
    // //         }
    // //         throw new Exception('Invalid Request');
    // //     } catch (Exception $e) {
    // //         return response()->json([
    // //             'success' => false,
    // //             'error' => $e->getMessage(),
    // //         ], 500);
    // //     }
    // // }
}
