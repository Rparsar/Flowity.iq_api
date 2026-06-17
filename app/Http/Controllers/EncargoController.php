<?php

namespace App\Http\Controllers;

use App\Models\Encargo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EncargoController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'total'    => Encargo::count(),
            'encargos' => Encargo::with('producto:id,nombre')->latest()->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio'      => 'numeric|min:0',
            'estado'      => 'in:activo,inactivo',
            'producto_id' => 'nullable|exists:productos,id',
            'dia_semana'  => 'nullable|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
        ]);

        $encargo = Encargo::create($validated);

        return response()->json([
            'message' => 'Encargo creado exitosamente',
            'encargo' => $encargo,
        ], 201);
    }

    public function show(Encargo $encargo): JsonResponse
    {
        return response()->json($encargo->load('producto'));
    }

    public function update(Request $request, Encargo $encargo): JsonResponse
    {
        $validated = $request->validate([
            'nombre'      => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'precio'      => 'sometimes|numeric|min:0',
            'estado'      => 'sometimes|in:activo,inactivo',
            'producto_id' => 'nullable|exists:productos,id',
            'dia_semana'  => 'nullable|in:lunes,martes,miercoles,jueves,viernes,sabado,domingo',
        ]);

        $encargo->update($validated);

        return response()->json([
            'message' => 'Encargo actualizado exitosamente',
            'encargo' => $encargo->fresh(),
        ], 200);
    }

    public function destroy(Encargo $encargo): JsonResponse
    {
        $encargo->delete();

        return response()->json([
            'message' => 'Encargo eliminado exitosamente',
        ], 200);
    }
}
