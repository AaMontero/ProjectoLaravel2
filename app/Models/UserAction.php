<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAction extends Model
{
    protected $fillable = ['user_id', 'action', 'entity_type', 'entity_id', 'modified_data'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'entity_id');
    }

    
}