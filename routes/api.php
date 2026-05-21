<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn() => ['status' => 'ok', 'service' => 'flowity-api']);

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('/dashboard/kpis', [DashboardController::class, 'kpis']);
Route::get('/dashboard/alertas', [DashboardController::class, 'alertas']);

// Productos
Route::apiResource('productos', ProductoController::class);

// Proveedores
Route::apiResource('proveedores', ProveedorController::class);

// Ventas
Route::apiResource('ventas', VentaController::class);
Route::get('/ventas/estadisticas/resumen', [VentaController::class, 'estadisticas']);
