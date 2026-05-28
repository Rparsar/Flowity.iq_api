<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = ['nombre', 'cliente', 'email', 'telefono', 'precio', 'fecha_inicio', 'fecha_fin', 'estado'];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function suscripciones()
    {
        return $this->morphMany(Suscripcion::class, 'suscriptible');
    }

    public function ventaDetalles()
    {
        return $this->morphMany(VentaDetalle::class, 'vendible');
    }
}
