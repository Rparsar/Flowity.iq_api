<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\ServicioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\EncargoController;
use App\Http\Controllers\SuscripcionController;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn() => ['status' => 'ok', 'service' => 'flowity-api']);

// Auth (públicas)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/kpis', [DashboardController::class, 'kpis']);
    Route::get('/dashboard/alertas', [DashboardController::class, 'alertas']);

    // Productos
    Route::apiResource('productos', ProductoController::class);

    // Proveedores
    Route::apiResource('proveedores', ProveedorController::class);

    // Ventas
    Route::get('/ventas/estadisticas/resumen', [VentaController::class, 'estadisticas']);
    Route::apiResource('ventas', VentaController::class);

    // Servicios
    Route::apiResource('servicios', ServicioController::class);

    // Reservas
    Route::apiResource('reservas', ReservaController::class);

    // Encargos
    Route::apiResource('encargos', EncargoController::class);

    // Suscripciones
    Route::apiResource('suscripciones', SuscripcionController::class);
});
