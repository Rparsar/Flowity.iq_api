<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Encargo extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'precio', 'estado', 'producto_id', 'dia_semana'];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function suscripciones()
    {
        return $this->morphMany(Suscripcion::class, 'suscriptible');
    }

    public function ventaDetalles()
    {
        return $this->morphMany(VentaDetalle::class, 'vendible');
    }
}
