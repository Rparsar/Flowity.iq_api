<?php

namespace Database\Seeders;

use App\Models\Reserva;
use Illuminate\Database\Seeder;

class ReservaSeeder extends Seeder
{
    public function run(): void
    {
        $reservas = [
            [
                'nombre'      => 'Sala de Reuniones A',
                'descripcion' => 'Sala equipada para 10 personas con proyector, pizarra y conexión WiFi.',
                'precio'      => 50.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Sala de Reuniones B',
                'descripcion' => 'Sala ejecutiva para 6 personas con pantalla 4K y sistema de videoconferencia.',
                'precio'      => 75.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Despacho Privado',
                'descripcion' => 'Espacio de trabajo individual con total privacidad, escritorio y silla ergonómica.',
                'precio'      => 30.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Espacio Coworking',
                'descripcion' => 'Zona de trabajo compartida con ambiente profesional, café incluido.',
                'precio'      => 20.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Auditorio Principal',
                'descripcion' => 'Auditorio con capacidad para 80 personas, escenario, sonido e iluminación profesional.',
                'precio'      => 300.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Terraza Eventos',
                'descripcion' => 'Espacio al aire libre para eventos, teambuilding y celebraciones.',
                'precio'      => 150.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Estudio Fotografía',
                'descripcion' => 'Estudio profesional con ciclorama, focos y equipamiento fotográfico disponible.',
                'precio'      => 120.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Laboratorio Informático',
                'descripcion' => 'Sala con 20 puestos equipados para formaciones y talleres tecnológicos.',
                'precio'      => 200.00,
                'estado'      => 'inactivo',
            ],
        ];

        foreach ($reservas as $reserva) {
            Reserva::create($reserva);
        }
    }
}
