<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $fillable = [
        'nombres', 'rol','porcentaje_ventas', 'user_id'
    ]; 

    public function contratos(){
        return $this->hasMany(Contrato::class, 'vendedor_id');
    }

    use HasFactory;

    
}
