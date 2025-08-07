<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pay extends Model
{
    // Agrega 'payment_method' a los campos asignables
    protected $fillable = ['amount', 'user_id', 'payment_method'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
