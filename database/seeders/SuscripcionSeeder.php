<?php

namespace Database\Seeders;

use App\Models\Suscripcion;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class SuscripcionSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener algunos productos existentes para asociar a las suscripciones
        $productos = Producto::all();
        
        $suscripciones = [
            [
                'nombre'      => 'Suscripción Mensual - Café Premium',
                'descripcion' => 'Recibe mensualmente 1kg de café premium de origen seleccionado. Entrega directa a domicilio.',
                'precio'      => 29.99,
                'planes'      => ['mensual'],
                'estado'      => 'activo',
                'producto_id' => $productos->firstWhere('nombre', 'like', '%café%')?->id ?? null,
            ],
            [
                'nombre'      => 'Suscripción Trimestral - Té Gourmet',
                'descripcion' => 'Cada 3 meses recibe una selección de 6 variedades de té gourmet de diferentes regiones.',
                'precio'      => 89.99,
                'planes'      => ['trimestral'],
                'estado'      => 'activo',
                'producto_id' => $productos->firstWhere('nombre', 'like', '%té%')?->id ?? null,
            ],
            [
                'nombre'      => 'Suscripción Anual - Aceite de Oliva Virgen',
                'descripcion' => 'Entrega anual de 12 botellas de aceite de oliva virgen extra de primera prensada en frío.',
                'precio'      => 299.99,
                'planes'      => ['semestral'],
                'estado'      => 'activo',
                'producto_id' => $productos->firstWhere('nombre', 'like', '%aceite%')?->id ?? null,
            ],
            [
                'nombre'      => 'Suscripción Mensual - Vinos Selectos',
                'descripcion' => 'Cada mes recibe 2 botellas de vino seleccionado por nuestros sommeliers. DOP y DOQ garantizados.',
                'precio'      => 49.99,
                'planes'      => ['mensual', 'trimestral', 'semestral'],
                'estado'      => 'activo',
                'producto_id' => $productos->firstWhere('nombre', 'like', '%vino%')?->id ?? null,
            ],
            [
                'nombre'      => 'Suscripción Bimestral - Chocolates Artesanales',
                'descripcion' => 'Cada 2 meses recibe una caja de chocolates artesanales con diferentes sabores y porcentajes de cacao.',
                'precio'      => 39.99,
                'planes'      => ['trimestral'],
                'estado'      => 'activo',
                'producto_id' => $productos->firstWhere('nombre', 'like', '%chocolate%')?->id ?? null,
            ],
            [
                'nombre'      => 'Suscripción Semanal - Pan Artesanal',
                'descripcion' => 'Entrega semanal de pan artesanal recién horneado. Variedad de tipos: integral, centeno, espelta.',
                'precio'      => 19.99,
                'planes'      => ['mensual'],
                'estado'      => 'activo',
                'producto_id' => $productos->firstWhere('nombre', 'like', '%pan%')?->id ?? null,
            ],
            [
                'nombre'      => 'Suscripción Mensual - Frutas de Temporada',
                'descripcion' => 'Cesta mensual con frutas de temporada seleccionadas. Productos locales y ecológicos.',
                'precio'      => 34.99,
                'planes'      => ['mensual', 'trimestral'],
                'estado'      => 'activo',
                'producto_id' => $productos->firstWhere('nombre', 'like', '%fruta%')?->id ?? null,
            ],
            [
                'nombre'      => 'Suscripción Trimestral - Quesos Gourmet',
                'descripcion' => 'Selección trimestral de quesos gourmet de diferentes regiones. Incluye tabla de maridaje.',
                'precio'      => 79.99,
                'planes'      => ['trimestral', 'semestral'],
                'estado'      => 'inactivo',
                'producto_id' => $productos->firstWhere('nombre', 'like', '%queso%')?->id ?? null,
            ],
        ];

        foreach ($suscripciones as $suscripcion) {
            Suscripcion::create($suscripcion);
        }
    }
}
