<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaDetalle extends Model
{
    use HasFactory;

    protected $table = 'venta_detalles';

    protected $fillable = [
        'venta_id',
        'vendible_id',
        'vendible_type',
        'nombre',
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

    public function vendible()
    {
        return $this->morphTo();
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'vendible_id')->where('vendible_type', 'App\Models\Producto');
    }
}
