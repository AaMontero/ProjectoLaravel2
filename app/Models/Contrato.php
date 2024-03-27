<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    use HasFactory;

    protected $fillable = [
        'ubicacion_sala', 'anios_contrato', 'monto_contrato',  'bono_hospedaje_qori_loyalty',
        'bono_hospedaje_internacional', 'valor_total_credito_directo', 'meses_credito_directo',
        'abono_credito_directo', 'valor_pagare', 'fecha_fin_pagare', 'comentario', 'otro_comentario',
        'otro_valor', 'user_id', 'cliente_id', 'vendedor_id', 'closer_id', 'closer2_id', 'jefe_sala_id',
        'contrato_id', 'bono_certificado_vacacional_internacional', 'personas_internacional', 'lugar_internacional',
        'bono_semana_internacional', 'bono_certificado_vacacional_internacional'
    ];

    public function Cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function Usuario()
    {
        return $this->belongsTo(User::class);
    }
    public function vendedor1()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }

    public function closer()
    {
        return $this->belongsTo(Vendedor::class, 'closer_id');
    }

    public function closer2()
    {
        return $this->belongsTo(Vendedor::class, 'closer2_id');
    }

    public function jefeSala()
    {
        return $this->belongsTo(Vendedor::class, 'jefe_sala_id');
    }
}
