<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncargoVenta extends Model
{
    use HasFactory;

    protected $table = 'encargo_ventas';

    protected $fillable = [
        'venta_id',
        'encargo_id',
        'fecha',
        'cantidad',
        'precio',
        'subtotal',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'fecha' => 'datetime',
        'cantidad' => 'integer',
    ];

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    public function encargo(): BelongsTo
    {
        return $this->belongsTo(Encargo::class);
    }
}
