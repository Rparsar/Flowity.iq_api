<?php

namespace Database\Seeders;

use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Database\Seeder;

class VentaSeeder extends Seeder
{
    public function run(): void
    {
        $ventas = [
            [
                'codigo' => 'V-2025-0001',
                'cliente' => 'Empresa ABC',
                'total' => 1979.96,
                'estado' => 'completada',
                'metodo_pago' => 'transferencia',
                'fecha' => '2025-06-15 10:30:00',
                'items' => [
                    ['producto_id' => 1, 'nombre' => 'Laptop HP ProBook 450 G8', 'cantidad' => 2, 'precio' => 899.99],
                    ['producto_id' => 3, 'nombre' => 'Teclado Mecánico Keychron K2', 'cantidad' => 2, 'precio' => 89.99],
                ],
            ],
            [
                'codigo' => 'V-2025-0002',
                'cliente' => 'María Rodríguez',
                'total' => 379.99,
                'estado' => 'completada',
                'metodo_pago' => 'tarjeta',
                'fecha' => '2025-06-14 14:15:00',
                'items' => [
                    ['producto_id' => 6, 'nombre' => 'Auriculares Sony WH-1000XM5', 'cantidad' => 1, 'precio' => 379.99],
                ],
            ],
            [
                'codigo' => 'V-2025-0003',
                'cliente' => 'Tienda Electrónica XYZ',
                'total' => 709.92,
                'estado' => 'pendiente',
                'metodo_pago' => 'transferencia',
                'fecha' => '2025-06-14 16:45:00',
                'items' => [
                    ['producto_id' => 4, 'nombre' => 'Mouse Logitech MX Master 3S', 'cantidad' => 5, 'precio' => 99.99],
                    ['producto_id' => 8, 'nombre' => 'Hub USB-C Anker 8 en 1', 'cantidad' => 3, 'precio' => 69.99],
                ],
            ],
            [
                'codigo' => 'V-2025-0004',
                'cliente' => 'Desarrollo Web Plus',
                'total' => 1319.94,
                'estado' => 'completada',
                'metodo_pago' => 'tarjeta',
                'fecha' => '2025-06-13 09:20:00',
                'items' => [
                    ['producto_id' => 2, 'nombre' => 'Monitor LG 27\" 4K UltraFine', 'cantidad' => 3, 'precio' => 349.99],
                    ['producto_id' => 3, 'nombre' => 'Teclado Mecánico Keychron K2', 'cantidad' => 3, 'precio' => 89.99],
                ],
            ],
            [
                'codigo' => 'V-2025-0005',
                'cliente' => 'Consultora Digital',
                'total' => 1159.97,
                'estado' => 'cancelada',
                'metodo_pago' => 'tarjeta',
                'fecha' => '2025-06-12 11:30:00',
                'items' => [
                    ['producto_id' => 1, 'nombre' => 'Laptop HP ProBook 450 G8', 'cantidad' => 1, 'precio' => 899.99],
                    ['producto_id' => 7, 'nombre' => 'Disco Duro Externo Samsung T7 1TB', 'cantidad' => 2, 'precio' => 129.99],
                ],
            ],
        ];

        foreach ($ventas as $ventaData) {
            $items = $ventaData['items'];
            unset($ventaData['items']);

            $venta = Venta::create($ventaData);

            foreach ($items as $item) {
                VentaDetalle::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['producto_id'],
                    'nombre' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $item['cantidad'] * $item['precio'],
                ]);
            }
        }
    }
}
