<?php

namespace App\Http\Controllers;

use App\Models\Notificacion;

class NotificacionController extends Controller
{
    public function leido(Notificacion $notificacion)
    {
        $descripcionLlega = $notificacion->descripcion;
        Notificacion::where('descripcion', $descripcionLlega)
            ->update(['visto' => 1]);
        if ($notificacion->tipo == "chat") {
            return redirect()->route('chat.chat');
        }
    }
}
