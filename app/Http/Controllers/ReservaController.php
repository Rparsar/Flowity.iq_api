<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'total'    => Reserva::count(),
            'reservas' => Reserva::latest()->get(),
        ], 201);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'numeric|min:0',
            'estado' => 'in:activo,inactivo',
        ]);

        $reserva = Reserva::create($validated);

        return response()->json([
            'message' => 'Reserva creada exitosamente',
            'reserva' => $reserva,
        ], 201);
    }

    public function show(Reserva $reserva): JsonResponse
    {
        return response()->json($reserva);
    }

    public function update(Request $request, Reserva $reserva): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string',
            'precio' => 'sometimes|numeric|min:0',
            'estado' => 'sometimes|in:activo,inactivo',
        ]);

        $reserva->update($validated);

        return response()->json([
            'message' => 'Reserva actualizada exitosamente',
            'reserva' => $reserva->fresh(),
        ], 201);
    }

    public function destroy(Reserva $reserva): JsonResponse
    {
        $reserva->delete();

        return response()->json([
            'message' => 'Reserva eliminada exitosamente',
        ], 201);
    }
}
