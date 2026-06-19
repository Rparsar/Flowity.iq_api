<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Obtener ventas agrupadas por semana del último mes (4 semanas)
     * Optimizado con GROUP BY para reducir queries
     */
    private function getVentasSemanales(): array
    {
        $hoy = Carbon::now();
        $inicioMes = $hoy->clone()->subWeeks(3)->startOfWeek();
        $finMes = $hoy->clone()->endOfWeek();

        $ventas = Venta::select([
                DB::raw("DATE_TRUNC('week', fecha) as semana_inicio"),
                DB::raw("SUM(CASE WHEN estado = 'completada' THEN total ELSE 0 END) as ventas"),
                DB::raw('COUNT(*) as pedidos')
            ])
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->groupBy('semana_inicio')
            ->orderBy('semana_inicio')
            ->get();

        // Mapear al formato esperado
        $resultado = [];
        foreach ($ventas as $venta) {
            $semanaInicio = Carbon::parse($venta->semana_inicio);
            $resultado[] = [
                'fecha'   => 'Sem ' . $semanaInicio->format('d/m'),
                'ventas'  => round((float) $venta->ventas, 2),
                'pedidos' => $venta->pedidos,
            ];
        }

        return $resultado;
    }

    /**
     * Obtener ventas agrupadas por día de los últimos 30 días
     * Optimizado con GROUP BY para reducir queries
     */
    private function getVentasDiarias(): array
    {
        $hoy = Carbon::now();
        $inicio = $hoy->clone()->subDays(29)->startOfDay();
        $fin = $hoy->clone()->endOfDay();

        $ventas = Venta::select([
                DB::raw('DATE(fecha) as dia'),
                DB::raw("SUM(CASE WHEN estado = 'completada' THEN total ELSE 0 END) as ventas"),
                DB::raw('COUNT(*) as pedidos')
            ])
            ->whereBetween('fecha', [$inicio, $fin])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        // Mapear al formato esperado
        $resultado = [];
        for ($i = 29; $i >= 0; $i--) {
            $dia = $hoy->clone()->subDays($i);
            $diaKey = $dia->toDateString();
            
            $ventaDia = $ventas->firstWhere('dia', $diaKey);
            
            $resultado[] = [
                'fecha'   => $dia->format('d/m'),
                'ventas'  => round((float) ($ventaDia->ventas ?? 0), 2),
                'pedidos' => $ventaDia->pedidos ?? 0,
            ];
        }

        return $resultado;
    }

    /**
     * Obtener histórico de ventas agrupadas por mes
     * Optimizado con GROUP BY para reducir queries
     */
    private function getVentasHistoricas($meses = 12): array
    {
        $hoy = Carbon::now();
        $inicio = $hoy->clone()->subMonths($meses - 1)->startOfMonth();
        $fin = $hoy->clone()->endOfMonth();

        $ventas = Venta::select([
                DB::raw("TO_CHAR(fecha, 'YYYY-MM') as mes"),
                DB::raw("SUM(CASE WHEN estado = 'completada' THEN total ELSE 0 END) as ventas"),
                DB::raw('COUNT(*) as pedidos')
            ])
            ->whereBetween('fecha', [$inicio, $fin])
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Mapear al formato esperado
        $ventasHistoricas = [];
        for ($i = $meses - 1; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $mesKey = $fecha->format('Y-m');
            
            $ventaMes = $ventas->firstWhere('mes', $mesKey);
            
            $ventasHistoricas[] = [
                'fecha' => $mesKey,
                'ventas' => round((float) ($ventaMes->ventas ?? 0), 2),
                'pedidos' => $ventaMes->pedidos ?? 0,
            ];
        }
        
        return $ventasHistoricas;
    }

    /**
     * Calcular cambio porcentual mes actual vs mes anterior
     */
    private function calcularCambioMensual(): array
    {
        $mesActual = Carbon::now();
        $mesAnterior = $mesActual->clone()->subMonth();
        
        // Ingresos mes actual
        $ingresosActual = (float) Venta::where('estado', 'completada')
            ->whereBetween('fecha', [
                $mesActual->clone()->startOfMonth(),
                $mesActual->clone()->endOfMonth()
            ])
            ->sum('total');
        
        // Ingresos mes anterior
        $ingresosAnterior = (float) Venta::where('estado', 'completada')
            ->whereBetween('fecha', [
                $mesAnterior->clone()->startOfMonth(),
                $mesAnterior->clone()->endOfMonth()
            ])
            ->sum('total');
        
        // Ventas mes actual
        $ventasActual = Venta::whereBetween('fecha', [
            $mesActual->clone()->startOfMonth(),
            $mesActual->clone()->endOfMonth()
        ])->count();
        
        // Ventas mes anterior
        $ventasAnterior = Venta::whereBetween('fecha', [
            $mesAnterior->clone()->startOfMonth(),
            $mesAnterior->clone()->endOfMonth()
        ])->count();
        
        // Calcular porcentajes
        $cambioIngresos = $ingresosAnterior > 0 
            ? round((($ingresosActual - $ingresosAnterior) / $ingresosAnterior) * 100, 1)
            : 0;
        
        $cambioVentas = $ventasAnterior > 0 
            ? round((($ventasActual - $ventasAnterior) / $ventasAnterior) * 100, 1)
            : 0;
        
        return [
            'ingresos_cambio' => $cambioIngresos,
            'ventas_cambio' => $cambioVentas,
        ];
    }

    public function index(): JsonResponse
    {
        // Productos con stock crítico (por debajo del mínimo) o bajo (1-2 unidades del mínimo)
        $alertasStock = Producto::where('estado', 'activo')
            ->where(function ($query) {
                $query->whereColumn('stock', '<', 'stock_minimo')
                    ->orWhereRaw('stock BETWEEN stock_minimo AND stock_minimo + 2');
            })
            ->get()
            ->map(function ($producto) {
                $estado_alerta = $producto->stock < $producto->stock_minimo ? 'critico' : 'bajo';
                return [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'stock' => $producto->stock,
                    'stock_minimo' => $producto->stock_minimo,
                    'estado_alerta' => $estado_alerta,
                ];
            });

        $ultimasVentas = Venta::latest('fecha')
            ->limit(5)
            ->get()
            ->map(function ($venta) {
                return [
                    'id' => $venta->id,
                    'codigo' => $venta->codigo,
                    'cliente' => $venta->cliente,
                    'total' => (float) $venta->total,
                    'fecha' => $venta->fecha,
                ];
            });

        $ingresosTotales = (float) Venta::where('estado', 'completada')->sum('total');
        $ventasTotales = Venta::count();
        $cambios = $this->calcularCambioMensual();

        $kpis = [
            'ingresos_totales' => $ingresosTotales,
            'ingresos_cambio' => $cambios['ingresos_cambio'],
            'ventas_totales' => $ventasTotales,
            'ventas_cambio' => $cambios['ventas_cambio'],
            'productos_stock' => Producto::where('estado', 'activo')->sum('stock'),
            'stock_bajo' => $alertasStock->count(),
            'clientes_nuevos' => 156,
            'clientes_cambio' => 24.3,
        ];

        $ventasHistoricas = $this->getVentasHistoricas(12);
        $ventasSemanales = $this->getVentasSemanales();

        return response()->json([
            'kpis' => $kpis,
            'ventas_historicas' => $ventasHistoricas,
            'ventas_semanales' => $ventasSemanales,
            'alertas_stock' => $alertasStock,
            'ultimas_ventas' => $ultimasVentas,
        ]);
    }

    public function kpis(): JsonResponse
    {
        $cambios = $this->calcularCambioMensual();
        
        return response()->json([
            'ingresos_totales' => (float) Venta::where('estado', 'completada')->sum('total'),
            'ingresos_cambio' => $cambios['ingresos_cambio'],
            'ventas_totales' => Venta::count(),
            'ventas_cambio' => $cambios['ventas_cambio'],
            'productos_stock' => Producto::where('estado', 'activo')->sum('stock'),
            'stock_bajo' => Producto::whereColumn('stock', '<', 'stock_minimo')->count(),
            'clientes_nuevos' => 156,
            'clientes_cambio' => 24.3,
        ]);
    }

    public function graficaEvolucion(): JsonResponse
    {
        return response()->json([
            'mensual'  => $this->getVentasHistoricas(12),
            'semanal'  => $this->getVentasSemanales(),
            'diario'   => $this->getVentasDiarias(),
        ]);
    }

    public function alertas(): JsonResponse
    {
        $alertas = Producto::whereColumn('stock', '<', 'stock_minimo')
            ->where('estado', 'activo')
            ->get();

        return response()->json($alertas);
    }
}
