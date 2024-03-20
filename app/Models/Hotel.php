<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{

    protected $fillable = ['hotel_nombre',  'imagen_hotel', 'pais', 'provincia', 'ciudad', 'num_h', 'modified_data', 'num_camas', 'precio'
                            ,'servicios', 'tipo_alojamiento', 'opiniones'];
    use HasFactory;
}
