<?php

namespace Database\Seeders;

use App\Models\Proveedor;
use Illuminate\Database\Seeder;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'nombre' => 'TechCorp Internacional',
                'contacto' => 'Carlos Martínez',
                'email' => 'carlos@techcorp.com',
                'telefono' => '+34 612 345 678',
                'direccion' => 'Calle Tecnología 45, Madrid',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'Suministros García',
                'contacto' => 'María García',
                'email' => 'maria@sgarcia.es',
                'telefono' => '+34 623 456 789',
                'direccion' => 'Avenida Principal 123, Barcelona',
                'estado' => 'activo',
            ],
            [
                'nombre' => 'ElectroImport Europa',
                'contacto' => 'Juan Pérez',
                'email' => 'juan@electroimport.eu',
                'telefono' => '+34 634 567 890',
                'direccion' => 'Polígono Industrial Norte, Valencia',
                'estado' => 'inactivo',
            ],
            [
                'nombre' => 'Componentes López',
                'contacto' => 'Ana López',
                'email' => 'ana@clopez.es',
                'telefono' => '+34 645 678 901',
                'direccion' => 'Calle Comercio 89, Sevilla',
                'estado' => 'activo',
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}
