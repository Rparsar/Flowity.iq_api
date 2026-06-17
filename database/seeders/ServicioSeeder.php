<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = [
            [
                'nombre'      => 'Consultoría Empresarial',
                'descripcion' => 'Asesoramiento estratégico para optimizar procesos y aumentar la rentabilidad del negocio.',
                'precio'      => 150.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Diseño Gráfico',
                'descripcion' => 'Creación de identidad visual, logotipos, flyers y material corporativo.',
                'precio'      => 80.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Desarrollo Web',
                'descripcion' => 'Desarrollo de páginas web y aplicaciones a medida con tecnologías modernas.',
                'precio'      => 1200.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Marketing Digital',
                'descripcion' => 'Gestión de redes sociales, SEO y campañas publicitarias online.',
                'precio'      => 350.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Fotografía Profesional',
                'descripcion' => 'Sesiones fotográficas para producto, eventos corporativos y retrato.',
                'precio'      => 200.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Mantenimiento Informático',
                'descripcion' => 'Soporte técnico, actualizaciones y resolución de incidencias en sistemas.',
                'precio'      => 60.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Traducción de Documentos',
                'descripcion' => 'Traducción profesional de textos técnicos y legales en múltiples idiomas.',
                'precio'      => 45.00,
                'estado'      => 'activo',
            ],
            [
                'nombre'      => 'Formación Online',
                'descripcion' => 'Cursos y talleres formativos sobre herramientas digitales y gestión empresarial.',
                'precio'      => 120.00,
                'estado'      => 'inactivo',
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
