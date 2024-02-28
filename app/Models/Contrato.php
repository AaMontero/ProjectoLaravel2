<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = ['ubicacion_sala', 'anios_contrato','monto_contrato',  'bono_hospedaje_qori_loyalty',
    'bono_hospedaje_internacional', 'valor_total_credito_directo', 'meses_credito_directo',
    'abono_credito_directo', 'valor_pagare', 'fecha_fin_pagare', 'comentario', 'otro_comentario',
    'otro_valor','user_id', 'cliente_id'];

    public function Cliente(){
        return $this->belongsTo(Cliente::class);
    }

    public function Usuario(){
        return $this->belongsTo(User::class);
    }


}
