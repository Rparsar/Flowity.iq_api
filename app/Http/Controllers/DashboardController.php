<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $alertasStock = Producto::whereColumn('stock', '<', 'stock_minimo')
            ->where('estado', 'activo')
            ->get();

        $ultimasVentas = Venta::with('detalles')
            ->latest('fecha')
            ->limit(5)
            ->get();

        $kpis = [
            'ingresos_totales' => (float) Venta::where('estado', 'completada')->sum('total'),
            'ingresos_cambio' => 12.5,
            'ventas_totales' => Venta::count(),
            'ventas_cambio' => 8.2,
            'productos_stock' => Producto::where('estado', 'activo')->sum('stock'),
            'stock_bajo' => $alertasStock->count(),
            'clientes_nuevos' => 156,
            'clientes_cambio' => 24.3,
        ];

        $ventasHistoricas = [
            ['fecha' => '2025-01', 'ventas' => 12500, 'pedidos' => 145],
            ['fecha' => '2025-02', 'ventas' => 14200, 'pedidos' => 168],
            ['fecha' => '2025-03', 'ventas' => 11800, 'pedidos' => 132],
            ['fecha' => '2025-04', 'ventas' => 15600, 'pedidos' => 189],
            ['fecha' => '2025-05', 'ventas' => 18900, 'pedidos' => 224],
            ['fecha' => '2025-06', 'ventas' => 21300, 'pedidos' => 256],
        ];

        return response()->json([
            'kpis' => $kpis,
            'ventas_historicas' => $ventasHistoricas,
            'alertas_stock' => $alertasStock,
            'ultimas_ventas' => $ultimasVentas,
        ]);
    }

    public function kpis(): JsonResponse
    {
        return response()->json([
            'ingresos_totales' => (float) Venta::where('estado', 'completada')->sum('total'),
            'ingresos_cambio' => 12.5,
            'ventas_totales' => Venta::count(),
            'ventas_cambio' => 8.2,
            'productos_stock' => Producto::where('estado', 'activo')->sum('stock'),
            'stock_bajo' => Producto::whereColumn('stock', '<', 'stock_minimo')->count(),
            'clientes_nuevos' => 156,
            'clientes_cambio' => 24.3,
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
