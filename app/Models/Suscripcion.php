<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Suscripcion extends Model
{
    use HasFactory;

    protected $table = 'suscripciones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'planes',
        'estado',
        'producto_id',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'planes' => 'array',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function suscripcionVentas(): HasMany
    {
        return $this->hasMany(SuscripcionVenta::class);
    }

    public function cancelar()
    {
        $this->estado = 'cancelada';
        $this->save();
    }
}
