<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos que se pueden asignar masivamente (por ejemplo, con create o fill).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance',
        'kind',
        'status',
    ];

    /**
     * Atributos que se deben ocultar al convertir el modelo a JSON o array.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos de datos (casts) para atributos específicos.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed', // Laravel 10+ encripta automáticamente con esto
        ];
    }

    /**
     * Relación: transacciones enviadas por este usuario.
     */
    public function sentTransactions()
    {
        return $this->hasMany(Transaction::class, 'sender_id');
    }

    /**
     * Relación: transacciones recibidas por este usuario.
     */
    public function receivedTransactions()
    {
        return $this->hasMany(Transaction::class, 'receiver_id');
    }
}
