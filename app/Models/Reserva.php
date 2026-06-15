<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'estado'];

    protected $casts = [];

    public function suscripciones()
    {
        return $this->morphMany(Suscripcion::class, 'suscriptible');
    }

    public function ventaDetalles()
    {
        return $this->morphMany(VentaDetalle::class, 'vendible');
    }
}
