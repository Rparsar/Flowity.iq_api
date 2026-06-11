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
            'encargos' => Encargo::latest()->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cliente' => 'required|string|max:255',
            'email' => 'required|email',
            'telefono' => 'required|string|max:20',
            'precio' => 'numeric|min:0',
            'fecha' => 'nullable|date_format:Y-m-d H:i:s',
            'estado' => 'in:activo,inactivo',
        ]);

        $encargo = Encargo::create($validated);

        return response()->json([
            'message' => 'Encargo creado exitosamente',
            'encargo' => $encargo,
        ], 201);
    }

    public function show(Encargo $encargo): JsonResponse
    {
        return response()->json($encargo);
    }

    public function update(Request $request, Encargo $encargo): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'cliente' => 'sometimes|string|max:255',
            'email' => 'sometimes|email',
            'telefono' => 'sometimes|string|max:20',
            'precio' => 'sometimes|numeric|min:0',
            'fecha' => 'nullable|date_format:Y-m-d H:i:s',
            'estado' => 'sometimes|in:activo,inactivo',
        ]);

        $encargo->update($validated);

        return response()->json([
            'message' => 'Encargo actualizado exitosamente',
            'encargo' => $encargo->fresh(),
        ]);
    }

    public function destroy(Encargo $encargo): JsonResponse
    {
        $encargo->delete();

        return response()->json([
            'message' => 'Encargo eliminado exitosamente',
        ]);
    }
}
