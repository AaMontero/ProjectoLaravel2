<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paquete extends Model
{
    use HasFactory;

    protected $fillable = [
        'message', 'user_id', 'nombre_paquete', 'num_dias', 'num_noches', 'precio_afiliado' , 'precio_no_afiliado', 'imagen_paquete'
    ]; 
    public function user(): BelongsTo{
        return $this ->belongsTo(User::class); 
    }
    public function incluye(){
        return $this->hasMany(CaracteristicaPaquete::class)
            ->orderByRaw("lugar = 'Incluye' DESC, lugar = 'No Incluye' DESC");
    }

}