<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
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

// Endpoints públicos para web cliente
Route::prefix('public')->group(function () {
    Route::get('/productos', [ProductoController::class, 'index']);
    Route::get('/productos/{producto}', [ProductoController::class, 'show']);
    Route::get('/servicios', [ServicioController::class, 'index']);
    Route::get('/servicios/{servicio}', [ServicioController::class, 'show']);
    Route::get('/reservas', [ReservaController::class, 'index']);
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show']);
    Route::get('/encargos', [EncargoController::class, 'index']);
    Route::get('/encargos/{encargo}', [EncargoController::class, 'show']);
    Route::get('/suscripciones', [SuscripcionController::class, 'index']);
    Route::get('/suscripciones/{suscripcion}', [SuscripcionController::class, 'show']);
    Route::post('/ventas', [VentaController::class, 'store']);
});

// Protegidas con Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/kpis', [DashboardController::class, 'kpis']);
    Route::get('/dashboard/alertas', [DashboardController::class, 'alertas']);
    Route::get('/dashboard/grafica-evolucion', [DashboardController::class, 'graficaEvolucion']);

    // Chat
    Route::post('/chat', [ChatController::class, 'chat']);

    // Productos
    Route::apiResource('productos', ProductoController::class);

    // Proveedores
    Route::apiResource('proveedores', ProveedorController::class);

    // Ventas
    Route::get('/ventas/estadisticas/resumen', [VentaController::class, 'getEstadisticasVentas']);
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
