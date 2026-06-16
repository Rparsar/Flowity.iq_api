<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';

    protected $fillable = [
        'codigo',
        'cliente',
        'nombre',
        'apellidos',
        'email',
        'telefono',
        'total',
        'estado',
        'metodo_pago',
        'fecha',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'fecha' => 'datetime',
    ];

    public function productoVentas(): HasMany
    {
        return $this->hasMany(ProductoVenta::class);
    }

    public function servicioVentas(): HasMany
    {
        return $this->hasMany(ServicioVenta::class);
    }

    public function reservaVentas(): HasMany
    {
        return $this->hasMany(ReservaVenta::class);
    }

    public function encargoVentas(): HasMany
    {
        return $this->hasMany(EncargoVenta::class);
    }
}
