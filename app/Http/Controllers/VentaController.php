<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\ProductoVenta;
use App\Models\ServicioVenta;
use App\Models\ReservaVenta;
use App\Models\EncargoVenta;
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
            ->with(['productoVentas', 'servicioVentas', 'reservaVentas', 'encargoVentas'])
            ->latest('fecha')
            ->get();

        return response()->json([
            'total'  => $ventas->count(),
            'ventas' => $ventas,
        ], 201);
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'nombre'      => 'required|string|max:255',
                'apellidos'   => 'required|string|max:255',
                'email'       => 'required|email',
                'telefono'    => 'required|string|max:20',
                'total'       => 'required|numeric|min:0',
                'estado'      => 'in:completada,pendiente,cancelada',
                'metodo_pago' => 'nullable|string|max:255',
                'items'       => 'required|array|min:1',
                'items.*.tipo'         => 'required|in:producto,servicio,reserva,encargo',
                'items.*.recurso_id'   => 'required|integer',
                'items.*.cantidad'     => 'sometimes|integer|min:1',
                'items.*.fecha'        => 'sometimes|date_format:Y-m-d H:i:s',
                'items.*.fecha_inicio' => 'sometimes|nullable|date_format:Y-m-d H:i:s',
                'items.*.fecha_fin'    => 'sometimes|nullable|date_format:Y-m-d H:i:s',
                'items.*.precio'       => 'required|numeric|min:0',
            ]);

            $venta = Venta::create([
                'codigo'      => 'V-' . now()->format('Y') . '-' . Str::padLeft((Venta::count() + 1), 4, '0'),
                'cliente'     => $validated['nombre'] . ' ' . $validated['apellidos'],
                'nombre'      => $validated['nombre'],
                'apellidos'   => $validated['apellidos'],
                'email'       => $validated['email'],
                'telefono'    => $validated['telefono'],
                'total'       => $validated['total'],
                'estado'      => $validated['estado'] ?? 'pendiente',
                'metodo_pago' => $validated['metodo_pago'] ?? null,
                'fecha'       => now(),
            ]);

            foreach ($validated['items'] as $item) {
                $subtotal = ($item['cantidad'] ?? 1) * $item['precio'];
                
                match($item['tipo']) {
                    'producto' => ProductoVenta::create([
                        'venta_id'    => $venta->id,
                        'producto_id' => $item['recurso_id'],
                        'cantidad'    => $item['cantidad'] ?? 1,
                        'precio'      => $item['precio'],
                        'subtotal'    => $subtotal,
                    ]),
                    'servicio' => ServicioVenta::create([
                        'venta_id'    => $venta->id,
                        'servicio_id' => $item['recurso_id'],
                        'precio'      => $item['precio'],
                        'subtotal'    => $subtotal,
                    ]),
                    'reserva' => ReservaVenta::create([
                        'venta_id'     => $venta->id,
                        'reserva_id'   => $item['recurso_id'],
                        'precio'       => $item['precio'],
                        'subtotal'     => $subtotal,
                        'fecha_inicio' => $item['fecha_inicio'] ?? null,
                        'fecha_fin'    => $item['fecha_fin'] ?? null,
                    ]),
                    'encargo' => EncargoVenta::create([
                        'venta_id'   => $venta->id,
                        'encargo_id' => $item['recurso_id'],
                        'fecha'      => $item['fecha'] ?? null,
                        'cantidad'   => $item['cantidad'] ?? 1,
                        'precio'     => $item['precio'],
                        'subtotal'   => $subtotal,
                    ]),
                };
            }

            return response()->json([
                'message' => 'Venta creada exitosamente',
                'venta' => $venta->load(['productoVentas', 'servicioVentas', 'reservaVentas', 'encargoVentas']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al crear venta',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Venta $venta): JsonResponse
    {
        return response()->json($venta->load(['productoVentas', 'servicioVentas', 'reservaVentas', 'encargoVentas']));
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
            'venta' => $venta->fresh()->load(['productoVentas', 'servicioVentas', 'reservaVentas', 'encargoVentas']),
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
