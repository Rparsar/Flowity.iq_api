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
     */
    private function getVentasSemanales(): array
    {
        $hoy = Carbon::now();
        $resultado = [];

        for ($i = 3; $i >= 0; $i--) {
            $inicioSemana = $hoy->clone()->subWeeks($i)->startOfWeek();
            $finSemana    = $inicioSemana->clone()->endOfWeek();

            $ingresos = (float) Venta::where('estado', 'completada')
                ->whereBetween('fecha', [$inicioSemana, $finSemana])
                ->sum('total');

            $pedidos = Venta::whereBetween('fecha', [$inicioSemana, $finSemana])->count();

            $resultado[] = [
                'fecha'   => 'Sem ' . $inicioSemana->format('d/m'),
                'ventas'  => round($ingresos, 2),
                'pedidos' => $pedidos,
            ];
        }

        return $resultado;
    }

    /**
     * Obtener ventas agrupadas por día de los últimos 30 días
     */
    private function getVentasDiarias(): array
    {
        $hoy = Carbon::now();
        $resultado = [];

        for ($i = 29; $i >= 0; $i--) {
            $dia = $hoy->clone()->subDays($i);

            $ingresos = (float) Venta::where('estado', 'completada')
                ->whereDate('fecha', $dia->toDateString())
                ->sum('total');

            $pedidos = Venta::whereDate('fecha', $dia->toDateString())->count();

            $resultado[] = [
                'fecha'   => $dia->format('d/m'),
                'ventas'  => round($ingresos, 2),
                'pedidos' => $pedidos,
            ];
        }

        return $resultado;
    }

    /**
     * Obtener histórico de ventas agrupadas por mes
     */
    private function getVentasHistoricas($meses = 12): array
    {
        $ventasHistoricas = [];
        
        for ($i = $meses - 1; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $inicio = $fecha->clone()->startOfMonth();
            $fin = $fecha->clone()->endOfMonth();
            
            $ingresos = (float) Venta::where('estado', 'completada')
                ->whereBetween('fecha', [$inicio, $fin])
                ->sum('total');
            
            $pedidos = Venta::whereBetween('fecha', [$inicio, $fin])->count();
            
            $ventasHistoricas[] = [
                'fecha' => $fecha->format('Y-m'),
                'ventas' => round($ingresos, 2),
                'pedidos' => $pedidos,
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

        $ultimasVentas = Venta::with(['productoVentas', 'servicioVentas', 'reservaVentas', 'encargoVentas'])
            ->latest('fecha')
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
