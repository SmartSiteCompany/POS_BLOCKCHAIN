<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;  // IMPORTANTE: Importa User para las relaciones

class Transaction extends Model
{
    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = ['sender_id', 'receiver_id', 'amount', 'type'];

    /**
     * Usuario que envió la transacción.
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Usuario que recibió la transacción.
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
