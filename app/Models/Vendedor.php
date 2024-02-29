<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $fillable = [
        'nombres', 'rol','porcentaje_ventas', 'user_id'
    ]; 

    public function pagosVendedor   (){
        return $this->hasMany(PagoVendedor::class);
    }

    use HasFactory;

    
}
