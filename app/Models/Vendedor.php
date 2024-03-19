<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $fillable = [
        'nombres', 'rol', 'porcentaje_ventas', 'activo', 'user_id', 'user_vend_id'
    ];

    public function pagosVendedor()
    {
        return $this->hasMany(PagoVendedor::class);
    }
    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_vend_id');
    }
    use HasFactory;
}
