<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Http;


class WebhookController extends Controller
{
    public function webhook(Request $request)
    {
        try {
            $tokenverifique = 'Verificacion';
            $query = $request->query();

            // Verifica si los parámetros están presentes
            if (isset($query['hub_mode']) && isset($query['hub_verify_token']) && isset($query['hub_challenge'])) {
                $mode = $query['hub_mode'];
                $token = $query['hub_verify_token'];
                $challenge = $query['hub_challenge'];

                if ($mode === 'subscribe' && $token === $tokenverifique) {
                    // Responde con código de estado 200 y el desafío proporcionado
                    http_response_code(200);
                    return response($challenge, 200)->header('Content-Type', 'text/plain');
                } else {
                    throw new Exception('Verificación fallida: token incorrecto o modo inválido.');
                }
            } else {
                throw new Exception('Parámetros de verificación faltantes');
            }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // public function webhook(Request $request)
    // {
    //     try{
    //         $tokenverifique = 'Verificacion';
    //         $query = $request->query();

    //         $mode = $query['hub_mode'];
    //         $token = $query['hub_verify_token'];
    //         $challenge = $query['hub_challenge'];

    //         if($mode && $token){
    //             if($mode === '' && $token == $tokenverifique){
    //                 return response($challenge, 200)->header('Content-Type', 'text/plain');
    //             }
    //         }

    //         throw new Exception('invalid request');
    //     }catch(Exception $e){
    //         return response()->json([
    //            'success' => false,
    //            'error' => $e->getMessage(),
    //         ], 500);
    //     }
    // }
}
