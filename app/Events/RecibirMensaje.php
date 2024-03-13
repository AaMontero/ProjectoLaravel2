<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RecibirMensaje implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $usuario; 
    public $mensaje; 
    public $horaMensaje; 
    public function __construct($usuario, $mensaje, $horaMensaje)
    {
        $this->usuario = $usuario; 
        $this->mensaje = $mensaje; 
        $this->horaMensaje = $horaMensaje; 
    }

    public function broadcastOn()
    {
        return 'whatsapp-channel';
    }
    public function broadcastAs()
    {
        return 'whatsapp-event'; 
    }
}
