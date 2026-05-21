<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Producto::query();

        if ($request->has('categoria')) {
            $query->where('categoria', $request->input('categoria'));
        }

        if ($request->has('estado')) {
            $query->where('estado', $request->input('estado'));
        }

        if ($request->boolean('stock_bajo')) {
            $query->whereColumn('stock', '<', 'stock_minimo');
        }

        $productos = $query->with('proveedor')->get();

        return response()->json([
            'total' => $productos->count(),
            'productos' => $productos,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string|max:255',
            'sku' => 'required|string|unique:productos,sku',
            'stock' => 'integer|min:0',
            'stock_minimo' => 'integer|min:0',
            'precio' => 'numeric|min:0',
            'costo' => 'numeric|min:0',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'estado' => 'in:activo,inactivo',
        ]);

        $producto = Producto::create($validated);

        return response()->json([
            'message' => 'Producto creado exitosamente',
            'producto' => $producto,
        ], 201);
    }

    public function show(Producto $producto): JsonResponse
    {
        return response()->json($producto->load('proveedor'));
    }

    public function update(Request $request, Producto $producto): JsonResponse
    {
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'nullable|string',
            'categoria' => 'nullable|string|max:255',
            'sku' => 'sometimes|string|unique:productos,sku,' . $producto->id,
            'stock' => 'sometimes|integer|min:0',
            'stock_minimo' => 'sometimes|integer|min:0',
            'precio' => 'sometimes|numeric|min:0',
            'costo' => 'sometimes|numeric|min:0',
            'proveedor_id' => 'nullable|exists:proveedores,id',
            'estado' => 'sometimes|in:activo,inactivo',
        ]);

        $producto->update($validated);

        return response()->json([
            'message' => 'Producto actualizado exitosamente',
            'producto' => $producto->fresh('proveedor'),
        ]);
    }

    public function destroy(Producto $producto): JsonResponse
    {
        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado exitosamente',
        ]);
    }
}
