<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula','cliente_user','nombres', 'apellidos', 'numTelefonico', "fecha_nacimiento", 'email', 'provincia' , 'ciudad', 'activo', 'user_id' 
    ]; 
    public function user(): BelongsTo{
        return $this ->belongsTo(User::class); 
    }
    public function contratos(){
        return $this->hasMany(Contrato::class); 
    }
}   
