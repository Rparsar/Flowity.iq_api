<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\ProductoVenta;
use App\Models\ServicioVenta;
use App\Models\ReservaVenta;
use App\Models\EncargoVenta;
use App\Models\SuscripcionVenta;
use App\Models\Suscripcion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VentaController extends Controller
{
    private function calcularFechaProximoPago(string $plan): \Carbon\Carbon
    {
        return match($plan) {
            'trimestral' => now()->addDays(90),
            'semestral' => now()->addDays(180),
            default => now()->addDays(30), // mensual
        };
    }

    private function crearSuscripcionVenta(int $ventaId, array $item): SuscripcionVenta
    {
        $suscripcion = Suscripcion::findOrFail($item['recurso_id']);
        
        // Validar que el plan seleccionado esté permitido
        $planSeleccionado = $item['plan'] ?? 'mensual';
        if (!in_array($planSeleccionado, $suscripcion->planes ?? ['mensual'])) {
            throw new \Exception("El plan '{$planSeleccionado}' no está permitido para esta suscripción");
        }

        $subtotal = ($item['cantidad'] ?? 1) * $item['precio'];

        return SuscripcionVenta::create([
            'venta_id'          => $ventaId,
            'suscripcion_id'    => $item['recurso_id'],
            'cantidad'          => $item['cantidad'] ?? 1,
            'precio'            => $item['precio'],
            'subtotal'          => $subtotal,
            'fecha_inicio'      => now(),
            'fecha_proximo_pago' => $this->calcularFechaProximoPago($planSeleccionado),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $ventas = Venta::when($request->estado, fn($q, $v) => $q->where('estado', $v))
            ->when($request->desde, fn($q, $v) => $q->whereDate('fecha', '>=', $v))
            ->when($request->hasta, fn($q, $v) => $q->whereDate('fecha', '<=', $v))
            ->with(['productoVentas', 'servicioVentas', 'reservaVentas', 'encargoVentas', 'suscripcionVentas'])
            ->latest('fecha')
            ->get();

        return response()->json([
            'total'  => $ventas->count(),
            'ventas' => $ventas,
        ], 200);
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
                'items.*.tipo'         => 'required|in:producto,servicio,reserva,encargo,suscripcion',
                'items.*.recurso_id'   => 'required|integer',
                'items.*.cantidad'     => 'sometimes|integer|min:1',
                'items.*.fecha'        => 'sometimes|date_format:Y-m-d H:i:s',
                'items.*.fecha_inicio' => 'sometimes|nullable|date_format:Y-m-d H:i:s',
                'items.*.fecha_fin'    => 'sometimes|nullable|date_format:Y-m-d H:i:s',
                'items.*.fecha_proximo_pago' => 'sometimes|nullable|date_format:Y-m-d H:i:s',
                'items.*.plan'         => 'sometimes|in:mensual,trimestral,semestral',
                'items.*.precio'       => 'required|numeric|min:0',
            ]);

            // Obtener el último código de venta existente para evitar duplicados
            $ultimaVenta = Venta::orderBy('id', 'desc')->first();
            $ultimoNumero = $ultimaVenta ? (int) substr($ultimaVenta->codigo, strrpos($ultimaVenta->codigo, '-') + 1) : 0;
            $nuevoNumero = $ultimoNumero + 1;

            $venta = Venta::create([
                'codigo'      => 'V-' . now()->format('Y') . '-' . Str::padLeft($nuevoNumero, 4, '0'),
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
                    'suscripcion' => $this->crearSuscripcionVenta($venta->id, $item),
                };
            }

            return response()->json([
                'message' => 'Venta creada exitosamente',
                'venta' => $venta->load(['productoVentas', 'servicioVentas', 'reservaVentas', 'encargoVentas', 'suscripcionVentas']),
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
        return response()->json($venta->load([
            'productoVentas.producto:id,nombre',
            'servicioVentas.servicio:id,nombre',
            'reservaVentas.reserva:id,nombre',
            'encargoVentas.encargo:id,nombre',
            'suscripcionVentas.suscripcion:id,nombre',
        ]));
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
        ], 200);
    }

    public function destroy(Venta $venta): JsonResponse
    {
        $venta->delete();

        return response()->json([
            'message' => 'Venta eliminada exitosamente',
        ], 200);
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
        ], 200);
    }

    public function getEstadisticasVentas(): JsonResponse
    {
        $totalVentas = Venta::count();
        $totalIngresos = Venta::where('estado', 'completada')->sum('total');
        $completadas = Venta::where('estado', 'completada')->count();
        $pendientes = Venta::where('estado', 'pendiente')->count();
        $canceladas = Venta::where('estado', 'cancelada')->count();

        return response()->json([
            'total_ventas' => $totalVentas,
            'total_ingresos' => (float) $totalIngresos,
            'completadas' => $completadas,
            'pendientes' => $pendientes,
            'canceladas' => $canceladas,
            'ticket_medio' => $totalVentas > 0 ? (float) ($totalIngresos / $totalVentas) : 0,
        ], 200);
    }
}
