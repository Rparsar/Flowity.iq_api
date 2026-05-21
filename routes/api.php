<?php

use Illuminate\Support\Facades\Route;

Route::get('/health', fn() => ['status' => 'ok', 'service' => 'flowity-api']);

// Aquí irán tus endpoints:
// Route::apiResource('productos', ProductoController::class);
// Route::apiResource('proveedores', ProveedorController::class);
// Route::apiResource('ventas', VentaController::class);
// Route::get('/dashboard', [DashboardController::class, 'index']);
