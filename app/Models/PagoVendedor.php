<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoVendedor extends Model
{
    protected $fillable = [
        'valor_pago', 'fecha_pago', 'concepto', 'estado'
    ]; 
    use HasFactory;
    public function Vendedor(){
        return $this->belongsTo(Vendedor::class); 
    }
}
