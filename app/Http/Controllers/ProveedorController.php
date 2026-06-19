<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $proveedores = Proveedor::when(
            $request->has('estado'),
            fn($query) => $query->where('estado', $request->input('estado'))
        )
        ->withCount('productos')
        ->get();

        return response()->json([
            'total' => $proveedores->count(),
            'proveedores' => $proveedores,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string',
            'estado' => 'in:activo,inactivo',
        ]);

        $proveedor = Proveedor::create($validated);

        return response()->json([
            'message' => 'Proveedor creado exitosamente',
            'proveedor' => $proveedor,
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $proveedor = Proveedor::find($id);
        
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }
        
        $proveedor->load('productos');
        
        return response()->json($proveedor);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $proveedor = Proveedor::find($id);
        
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }
        
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'contacto' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string',
            'estado' => 'sometimes|in:activo,inactivo',
        ]);

        $proveedor->update($validated);

        return response()->json([
            'message' => 'Proveedor actualizado exitosamente',
            'proveedor' => $proveedor->load('productos'),
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $proveedor = Proveedor::find($id);
        
        if (!$proveedor) {
            return response()->json(['error' => 'Proveedor no encontrado'], 404);
        }
        
        $proveedor->delete();

        return response()->json([
            'message' => 'Proveedor eliminado exitosamente',
        ], 200);
    }
}
