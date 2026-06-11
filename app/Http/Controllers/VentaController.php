<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VentaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $ventas = Venta::when($request->estado, fn($q, $v) => $q->where('estado', $v))
            ->when($request->desde, fn($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($request->hasta, fn($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->with('detalles')
            ->latest('fecha')
            ->get();

        return response()->json([
            'total'  => $ventas->count(),
            'ventas' => $ventas,
        ], 201);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cliente' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'estado' => 'in:completada,pendiente,cancelada',
            'metodo_pago' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.vendible_id' => 'nullable|integer',
            'items.*.vendible_type' => 'nullable|string',
            'items.*.nombre' => 'required|string',
            'items.*.cantidad' => 'required|integer|min:1',
            'items.*.precio' => 'required|numeric|min:0',
        ]);

        $venta = Venta::create([
            'codigo' => 'V-' . now()->format('Y') . '-' . Str::padLeft((Venta::count() + 1), 4, '0'),
            'cliente' => $validated['cliente'],
            'total' => $validated['total'],
            'estado' => $validated['estado'] ?? 'pendiente',
            'metodo_pago' => $validated['metodo_pago'] ?? null,
            'fecha' => now(),
        ]);

        //crear detalle de venta iterando item
        foreach ($validated['items'] as $item) {
            VentaDetalle::create([
                'venta_id' => $venta->id,
                'producto_id' => $item['producto_id'] ?? null,
                'nombre' => $item['nombre'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio'],
                'subtotal' => $item['cantidad'] * $item['precio'],
            ]);
        }

        return response()->json([
            'message' => 'Venta creada exitosamente',
            'venta' => $venta->load('detalles'),
        ], 201);
    }

    public function show(Venta $venta): JsonResponse
    {
        return response()->json($venta->load('detalles'));
    }

    public function update(Request $request, Venta $venta): JsonResponse
    {
        $validated = $request->validate([
            'cliente' => 'sometimes|string|max:255',
            'total' => 'sometimes|numeric|min:0',
            'estado' => 'sometimes|in:completada,pendiente,cancelada',
            'metodo_pago' => 'nullable|string|max:255',
        ]);

        $venta->update($validated);

        return response()->json([
            'message' => 'Venta actualizada exitosamente',
            'venta' => $venta->fresh('detalles'),
        ], 201);
    }

    public function destroy(Venta $venta): JsonResponse
    {
        $venta->delete();

        return response()->json([
            'message' => 'Venta eliminada exitosamente',
        ], 201);
    }

    public function estadisticas(): JsonResponse
    {
        $totalVentas = Venta::count();
        $totalIngresos = Venta::where('estado', 'completada')->sum('total');
        $completadas = Venta::where('estado', 'completada')->count();
        $pendientes = Venta::where('estado', 'pendiente')->count();

        return response()->json([
            'total_ventas' => $totalVentas,
            'total_ingresos' => (float) $totalIngresos,
            'completadas' => $completadas,
            'pendientes' => $pendientes,
            'ticket_medio' => $totalVentas > 0 ? (float) ($totalIngresos / $totalVentas) : 0,
        ], 201);
    }
}
