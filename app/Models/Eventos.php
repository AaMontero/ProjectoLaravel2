<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eventos extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'start_date', 'end_date', 'cliente_id','hotel_nombre', 'user_id'
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class,);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
