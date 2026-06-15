<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductoVenta extends Model
{
    use HasFactory;

    protected $table = 'producto_ventas';

    protected $fillable = [
        'venta_id',
        'producto_id',
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'cantidad',
        'precio',
        'subtotal',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'cantidad' => 'integer',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }
}
