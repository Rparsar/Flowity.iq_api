<?php

namespace App\Http\Controllers;

use App\Models\Servicio;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index(): JsonResponse
    {
        $servicios = Servicio::all();

        return response()->json([
            'total' => $servicios->count(),
            'servicios' => $servicios,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'numeric|min:0',
            'estado' => 'in:activo,inactivo',
        ]);

        $servicio = Servicio::create($validated);

        return response()->json([
            'message' => 'Servicio creado exitosamente',
            'servicio' => $servicio,
        ], 201);
    }

    public function show(Servicio $servicio): JsonResponse
    {
        return response()->json($servicio);
    }

    public function update(Request $request, Servicio $servicio): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'sometimes|numeric|min:0',
            'estado' => 'sometimes|in:activo,inactivo',
        ]);

        $servicio->update($validated);

        return response()->json([
            'message' => 'Servicio actualizado exitosamente',
            'servicio' => $servicio->fresh(),
        ]);
    }

    public function destroy(Servicio $servicio): JsonResponse
    {
        $servicio->delete();

        return response()->json([
            'message' => 'Servicio eliminado exitosamente',
        ]);
    }
}
