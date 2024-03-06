<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsApp extends Model
{
    use HasFactory;

    protected $table = 'whats_apps';

    protected $fillable = [
        'fecha_hora',
        'mensaje_enviado',
        'id_wa',
        'timestamp_wa',
        'telefono_wa',
        'id_numCliente', 
        'visto', 
    ];

    protected $casts = [
        'fecha_hora' => 'datetime', 
    ];
}
