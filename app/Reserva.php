<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Cliente;
use App\Habitacion;

class Reserva extends Model
{
    //
    protected $guarded = [];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }
}
