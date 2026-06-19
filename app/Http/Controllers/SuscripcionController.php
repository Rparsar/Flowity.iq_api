<?php

namespace App\Http\Controllers;

use App\Models\Suscripcion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuscripcionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $suscripciones = Suscripcion::when($request->estado, fn($q, $v) => $q->where('estado', $v))
            ->with('producto')
            ->latest()
            ->get();

        return response()->json([
            'total' => $suscripciones->count(),
            'suscripciones' => $suscripciones,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'planes' => 'required|array',
            'planes.*' => 'in:mensual,trimestral,semestral',
            'estado' => 'in:activo,inactivo',
            'producto_id' => 'nullable|exists:productos,id',
        ]);

        $suscripcion = Suscripcion::create($validated);

        return response()->json([
            'message' => 'Suscripción creada exitosamente',
            'suscripcion' => $suscripcion->load('producto'),
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $suscripcion = Suscripcion::find($id);
        
        if (!$suscripcion) {
            return response()->json(['error' => 'Suscripción no encontrada'], 404);
        }
        
        return response()->json($suscripcion->load('producto'), 200);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $suscripcion = Suscripcion::find($id);
        
        if (!$suscripcion) {
            return response()->json(['error' => 'Suscripción no encontrada'], 404);
        }
        
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|nullable|string',
            'precio' => 'sometimes|numeric|min:0',
            'planes' => 'sometimes|array',
            'planes.*' => 'in:mensual,trimestral,semestral',
            'estado' => 'sometimes|in:activo,inactivo',
            'producto_id' => 'sometimes|nullable|exists:productos,id',
        ]);

        $suscripcion->update($validated);

        return response()->json([
            'message' => 'Suscripción actualizada exitosamente',
            'suscripcion' => $suscripcion->load('producto'),
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $suscripcion = Suscripcion::find($id);
        
        if (!$suscripcion) {
            return response()->json(['error' => 'Suscripción no encontrada'], 404);
        }
        
        $suscripcion->delete();

        return response()->json([
            'message' => 'Suscripción eliminada exitosamente',
        ], 200);
    }
}
