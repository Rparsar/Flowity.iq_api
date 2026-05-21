<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'nombre' => 'Laptop HP ProBook 450 G8',
                'descripcion' => 'Portátil empresarial 15.6\" FHD, Intel Core i5, 16GB RAM, 512GB SSD',
                'categoria' => 'Electrónica',
                'sku' => 'LAP-HP-450-001',
                'stock' => 12,
                'stock_minimo' => 5,
                'precio' => 899.99,
                'costo' => 650.00,
                'proveedor_id' => 1,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Monitor LG 27\" 4K UltraFine',
                'descripcion' => 'Monitor profesional 4K IPS, HDR400, USB-C',
                'categoria' => 'Electrónica',
                'sku' => 'MON-LG-27-4K',
                'stock' => 3,
                'stock_minimo' => 10,
                'precio' => 349.99,
                'costo' => 280.00,
                'proveedor_id' => 2,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Teclado Mecánico Keychron K2',
                'descripcion' => 'Teclado mecánico inalámbrico RGB, switches Brown, layout español',
                'categoria' => 'Accesorios',
                'sku' => 'TEC-KEY-K2-RGB',
                'stock' => 25,
                'stock_minimo' => 10,
                'precio' => 89.99,
                'costo' => 55.00,
                'proveedor_id' => 1,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Mouse Logitech MX Master 3S',
                'descripcion' => 'Ratón inalámbrico ergonómico, sensor 8000 DPI, multi-dispositivo',
                'categoria' => 'Accesorios',
                'sku' => 'MOU-LOG-MX3S',
                'stock' => 45,
                'stock_minimo' => 20,
                'precio' => 99.99,
                'costo' => 70.00,
                'proveedor_id' => 2,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Webcam Logitech Brio 4K',
                'descripcion' => 'Cámara web 4K con HDR, micrófono integrado, para streaming',
                'categoria' => 'Electrónica',
                'sku' => 'WEB-LOG-BRIO4K',
                'stock' => 2,
                'stock_minimo' => 8,
                'precio' => 159.99,
                'costo' => 120.00,
                'proveedor_id' => 2,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Auriculares Sony WH-1000XM5',
                'descripcion' => 'Auriculares inalámbricos con cancelación de ruido, 30h batería',
                'categoria' => 'Audio',
                'sku' => 'AUD-SONY-XM5',
                'stock' => 18,
                'stock_minimo' => 10,
                'precio' => 379.99,
                'costo' => 290.00,
                'proveedor_id' => 3,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Disco Duro Externo Samsung T7 1TB',
                'descripcion' => 'SSD portátil USB-C, velocidad de transferencia 1050 MB/s',
                'categoria' => 'Almacenamiento',
                'sku' => 'SSD-SAM-T7-1TB',
                'stock' => 8,
                'stock_minimo' => 5,
                'precio' => 129.99,
                'costo' => 95.00,
                'proveedor_id' => 1,
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Hub USB-C Anker 8 en 1',
                'descripcion' => 'Docking station USB-C con HDMI 4K, Ethernet, USB-A, lector SD',
                'categoria' => 'Accesorios',
                'sku' => 'HUB-ANK-8IN1',
                'stock' => 32,
                'stock_minimo' => 15,
                'precio' => 69.99,
                'costo' => 45.00,
                'proveedor_id' => 4,
                'estado' => 'activo',
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
