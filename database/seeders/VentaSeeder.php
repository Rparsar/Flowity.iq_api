<?php

namespace Database\Seeders;

use App\Models\Venta;
use App\Models\ProductoVenta;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VentaSeeder extends Seeder
{
    public function run(): void
    {
        // Generar ventas históricas para últimos 12 meses
        $clientes = ['Empresa ABC', 'María Rodríguez', 'Tienda Electrónica XYZ', 'Desarrollo Web Plus', 'Consultora Digital'];
        $metodos = ['tarjeta', 'transferencia', 'paypal'];
        $estados = ['completada', 'pendiente', 'cancelada'];
        
        $ventaCounter = 1;
        
        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $ventasEnMes = rand(3, 8);
            
            for ($j = 0; $j < $ventasEnMes; $j++) {
                $fechaVenta = $fecha->clone()
                    ->day(rand(1, $fecha->daysInMonth))
                    ->hour(rand(8, 18))
                    ->minute(rand(0, 59));
                
                $total = rand(300, 2000);
                $estado = $j % 5 === 0 ? 'cancelada' : ($j % 3 === 0 ? 'pendiente' : 'completada');
                
                $venta = Venta::create([
                    'codigo' => 'V-' . $fecha->format('Y') . '-' . str_pad($ventaCounter, 4, '0', STR_PAD_LEFT),
                    'cliente' => $clientes[array_rand($clientes)],
                    'nombre' => 'Cliente',
                    'apellidos' => 'Prueba',
                    'email' => 'cliente' . $ventaCounter . '@example.com',
                    'telefono' => '+34 ' . rand(600000000, 699999999),
                    'total' => $total,
                    'estado' => $estado,
                    'metodo_pago' => $metodos[array_rand($metodos)],
                    'fecha' => $fechaVenta,
                ]);
                
                // Agregar producto a la venta
                ProductoVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => rand(1, 8),
                    'cantidad' => rand(1, 5),
                    'precio' => $total,
                    'subtotal' => $total,
                ]);
                
                $ventaCounter++;
            }
        }
    }
}
