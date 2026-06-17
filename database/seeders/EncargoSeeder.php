<?php

namespace Database\Seeders;

use App\Models\Encargo;
use Illuminate\Database\Seeder;

class EncargoSeeder extends Seeder
{
    public function run(): void
    {
        $diasSemana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes'];

        $encargos = [
            [
                'nombre'      => 'Cesta de Frutas Semanal',
                'descripcion' => 'Selección de frutas de temporada frescas entregadas semanalmente.',
                'precio'      => 25.00,
                'estado'      => 'activo',
                'producto_id' => 1,
                'dia_semana'  => 'lunes',
            ],
            [
                'nombre'      => 'Pan Artesano Diario',
                'descripcion' => 'Hogaza de pan artesano elaborada con masa madre y harinas ecológicas.',
                'precio'      => 4.50,
                'estado'      => 'activo',
                'producto_id' => 2,
                'dia_semana'  => 'martes',
            ],
            [
                'nombre'      => 'Caja Verduras Ecológicas',
                'descripcion' => 'Verduras ecológicas de producción local, surtido variable según temporada.',
                'precio'      => 18.00,
                'estado'      => 'activo',
                'producto_id' => 3,
                'dia_semana'  => 'miercoles',
            ],
            [
                'nombre'      => 'Lácteos Frescos',
                'descripcion' => 'Selección de quesos, yogures y leche fresca de ganadería local.',
                'precio'      => 12.00,
                'estado'      => 'activo',
                'producto_id' => 4,
                'dia_semana'  => 'jueves',
            ],
            [
                'nombre'      => 'Pedido Carnicería Premium',
                'descripcion' => 'Selección de carnes premium cortadas y preparadas al gusto del cliente.',
                'precio'      => 45.00,
                'estado'      => 'activo',
                'producto_id' => 5,
                'dia_semana'  => 'viernes',
            ],
            [
                'nombre'      => 'Cesta Navideña',
                'descripcion' => 'Encargo especial de cesta navideña con productos gourmet seleccionados.',
                'precio'      => 89.00,
                'estado'      => 'activo',
                'producto_id' => 6,
                'dia_semana'  => 'lunes',
            ],
            [
                'nombre'      => 'Flores Semanales',
                'descripcion' => 'Ramo de flores frescas de temporada entregado semanalmente.',
                'precio'      => 22.00,
                'estado'      => 'activo',
                'producto_id' => 7,
                'dia_semana'  => 'martes',
            ],
            [
                'nombre'      => 'Café de Especialidad',
                'descripcion' => 'Paquete mensual de café de especialidad en grano, selección de origen único.',
                'precio'      => 16.00,
                'estado'      => 'inactivo',
                'producto_id' => 8,
                'dia_semana'  => 'miercoles',
            ],
        ];

        foreach ($encargos as $encargo) {
            Encargo::create($encargo);
        }
    }
}
