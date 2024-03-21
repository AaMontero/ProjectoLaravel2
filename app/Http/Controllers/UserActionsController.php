<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Contrato;
use App\Models\Eventos;
use App\Models\Hotel;
use App\Models\Paquete;
use App\Models\User;
use App\Models\UserAction;
use App\Models\Vendedor;

class UserActionsController extends Controller
{
    public function index()
    {
        $datos = UserAction::all();
        $clientes = Cliente::all();
        $vendedor = Vendedor::all();
        $user = User::all();
        $contrato = Contrato::all();
        $paquete = Paquete::all();
        $evento = Eventos::all();
        $hotel = Hotel::all();
       

        return view('logs.log', ['datos' => $datos, 
                                'clientes' =>$clientes,
                                'vendedor' =>$vendedor,
                                'user' =>$user,
                                'contrato' =>$contrato,
                                'paquete' => $paquete,
                                'evento' => $evento,
                                'hotel' => $hotel]);
    }
}
