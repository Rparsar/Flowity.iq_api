<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuscripcionVenta extends Model
{
    use HasFactory;

    protected $table = 'suscripcion_ventas';

    protected $fillable = [
        'venta_id',
        'suscripcion_id',
        'cantidad',
        'precio',
        'subtotal',
        'fecha_inicio',
        'fecha_proximo_pago',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'fecha_inicio' => 'datetime',
        'fecha_proximo_pago' => 'datetime',
        'cantidad' => 'integer',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function suscripcion(): BelongsTo
    {
        return $this->belongsTo(Suscripcion::class);
    }
}
