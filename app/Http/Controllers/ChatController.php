<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChatController extends Controller
{
    /**
     * Procesar consulta del chat y devolver respuesta basada en datos reales
     */
    public function chat(Request $request): JsonResponse
    {
        $request->validate([
            'mensaje' => 'required|string|max:500',
        ]);

        $mensaje = strtolower($request->mensaje);
        $respuesta = $this->generarRespuesta($mensaje);

        return response()->json([
            'respuesta' => $respuesta,
        ]);
    }

    /**
     * Generar respuesta basada en datos reales de la base de datos
     */
    private function generarRespuesta(string $mensaje): string
    {
        // Consulta específica: stock actual
        if (str_contains($mensaje, 'stock actual') || str_contains($mensaje, '¿cuál es el stock')) {
            return $this->consultarStockActual();
        }

        // Consulta específica: productos con stock bajo
        if (str_contains($mensaje, 'stock bajo') || str_contains($mensaje, 'bajo')) {
            return $this->consultarStockBajo();
        }

        // Consultas sobre ventas
        if (str_contains($mensaje, 'venta') || str_contains($mensaje, 'facturación') || str_contains($mensaje, 'ingresos')) {
            return $this->consultarVentas($mensaje);
        }

        // Consultas sobre productos rentables
        if (str_contains($mensaje, 'rentable') || str_contains($mensaje, 'margen') || str_contains($mensaje, 'beneficio')) {
            return $this->consultarRentabilidad($mensaje);
        }

        // Consultas sobre proveedores
        if (str_contains($mensaje, 'proveedor')) {
            return $this->consultarProveedores($mensaje);
        }

        // Respuesta por defecto
        return "Entiendo tu consulta. Como asistente especializado en Flowity.iq, puedo ayudarte con información sobre stock, ventas, proveedores y análisis de tu negocio. Puedes preguntarme sobre: stock actual, ventas de este periodo, productos con stock bajo, o cuál es el producto más rentable.";
    }

    /**
     * Consultar stock actual total
     */
    private function consultarStockActual(): string
    {
        $totalStock = Producto::sum('stock');
        return "Actualmente tienes {$totalStock} productos en stock.";
    }

    /**
     * Consultar productos con stock bajo
     */
    private function consultarStockBajo(): string
    {
        $stockBajo = Producto::whereColumn('stock', '<', 'stock_minimo')->count();
        $productosBajo = Producto::whereColumn('stock', '<', 'stock_minimo')
            ->orderBy('stock', 'asc')
            ->limit(3)
            ->get(['nombre', 'stock', 'stock_minimo']);

        if ($stockBajo === 0) {
            return "No hay productos con stock bajo. Todos los productos están por encima del mínimo recomendado.";
        }

        $respuesta = "{$stockBajo} productos están por debajo del mínimo recomendado";

        if ($productosBajo->count() > 0) {
            $nombres = $productosBajo->pluck('nombre')->join(', ');
            $respuesta .= ", incluyendo: {$nombres}.";
        } else {
            $respuesta .= ".";
        }

        return $respuesta;
    }

    /**
     * Consultar información de ventas
     */
    private function consultarVentas(string $mensaje): string
    {
        $hoy = Carbon::now();
        
        // Determinar periodo
        if (str_contains($mensaje, 'hoy')) {
            $inicio = $hoy->startOfDay();
            $fin = $hoy->endOfDay();
            $periodo = "hoy";
        } elseif (str_contains($mensaje, 'ayer')) {
            $inicio = $hoy->subDay()->startOfDay();
            $fin = $hoy->endOfDay();
            $periodo = "ayer";
        } elseif (str_contains($mensaje, 'mes') || str_contains($mensaje, 'este mes')) {
            $inicio = $hoy->startOfMonth();
            $fin = $hoy->endOfMonth();
            $periodo = "este mes";
        } else {
            // Por defecto: esta semana (lunes a domingo)
            $inicio = $hoy->copy()->startOfWeek();
            $fin = $hoy->copy()->endOfWeek();
            $periodo = "esta semana";
        }

        $numVentas = Venta::whereBetween('fecha', [$inicio, $fin])
            ->where('estado', 'completada')
            ->count();
        $totalVentas = Venta::whereBetween('fecha', [$inicio, $fin])
            ->where('estado', 'completada')
            ->sum('total');

        $respuesta = "{$periodo} has realizado {$numVentas} ventas por un total de €" . number_format($totalVentas, 2);

        // Encontrar el día con más ventas
        $ventasPorDia = Venta::select([
                \Illuminate\Support\Facades\DB::raw('DATE(fecha) as dia'),
                \Illuminate\Support\Facades\DB::raw('SUM(total) as total'),
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as num_ventas')
            ])
            ->whereBetween('fecha', [$inicio, $fin])
            ->where('estado', 'completada')
            ->groupBy('dia')
            ->orderBy('total', 'desc')
            ->first();

        if ($ventasPorDia) {
            $diaMejor = Carbon::parse($ventasPorDia->dia)->format('l');
            $respuesta .= ". El día con más ventas fue {$diaMejor} con €" . number_format($ventasPorDia->total, 2);
        }

        return $respuesta . ".";
    }

    /**
     * Consultar rentabilidad de productos
     */
    private function consultarRentabilidad(string $mensaje): string
    {
        // Nota: Para calcular rentabilidad real necesitaríamos precio de costo
        // Por ahora usamos el precio como referencia
        $productosTop = Producto::orderBy('precio', 'desc')
            ->limit(3)
            ->get(['nombre', 'precio']);

        if ($productosTop->isEmpty()) {
            return "No hay productos registrados para analizar rentabilidad.";
        }

        $nombres = $productosTop->pluck('nombre')->join(', ');
        $precios = $productosTop->pluck('precio')->map(fn($p) => '€' . number_format($p, 2))->join(', ');

        return "Los productos con mayor precio son: {$nombres} con precios de {$precios} respectivamente. Para un análisis de rentabilidad más detallado, necesitaríamos información sobre los costos de los productos.";
    }

    /**
     * Consultar información de proveedores
     */
    private function consultarProveedores(string $mensaje): string
    {
        $totalProveedores = Proveedor::count();

        if ($totalProveedores === 0) {
            return "No hay proveedores registrados en el sistema.";
        }

        return "Actualmente tienes {$totalProveedores} proveedores registrados en el sistema. Puedes consultar el detalle de cada uno en la sección de Proveedores.";
    }
}
