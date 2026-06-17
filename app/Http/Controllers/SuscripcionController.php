<?php

namespace App\Http\Controllers;

use App\Models\Suscripcion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'total'         => Suscripcion::count(),
            'suscripciones' => Suscripcion::with('suscriptible')->latest()->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'suscriptible_id' => 'required|integer',
            'suscriptible_type' => 'required|string|in:App\Models\Producto,App\Models\Servicio,App\Models\Reserva,App\Models\Encargo',
            'tipo_periodo' => 'required|in:dia,semana,mes,año',
            'cantidad_periodos' => 'required|integer|min:1',
            'fecha_inicio' => 'required|date_format:Y-m-d H:i:s',
            'fecha_proximo_pago' => 'required|date_format:Y-m-d H:i:s',
            'estado' => 'in:activa,pausada,cancelada',
        ]);

        $suscripcion = Suscripcion::create($validated);

        return response()->json([
            'message' => 'Suscripción creada exitosamente',
            'suscripcion' => $suscripcion->load('suscriptible'),
        ], 201);
    }

    public function show(Suscripcion $suscripcion): JsonResponse
    {
        return response()->json($suscripcion->load('suscriptible'));
    }

    public function update(Request $request, Suscripcion $suscripcion): JsonResponse
    {
        $validated = $request->validate([
            'tipo_periodo' => 'sometimes|in:dia,semana,mes,año',
            'cantidad_periodos' => 'sometimes|integer|min:1',
            'fecha_inicio' => 'sometimes|date_format:Y-m-d H:i:s',
            'fecha_proximo_pago' => 'sometimes|date_format:Y-m-d H:i:s',
            'estado' => 'sometimes|in:activa,pausada,cancelada',
        ]);

        $suscripcion->update($validated);

        return response()->json([
            'message' => 'Suscripción actualizada exitosamente',
            'suscripcion' => $suscripcion->fresh('suscriptible'),
        ], 200);
    }

    public function destroy(Suscripcion $suscripcion): JsonResponse
    {
        $suscripcion->delete();

        return response()->json([
            'message' => 'Suscripción eliminada exitosamente',
        ], 200);
    }
}
