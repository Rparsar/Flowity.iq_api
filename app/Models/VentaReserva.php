<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaReserva extends Model
{
    protected $table = 'reserva_ventas';

    protected $fillable = [
        'venta_id',
        'reserva_id',
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'precio',
        'subtotal',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class);
    }
}
