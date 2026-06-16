<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Eliminar columnas de producto_ventas
        Schema::table('producto_ventas', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellidos', 'email', 'telefono']);
        });

        // Eliminar columnas de servicio_ventas
        Schema::table('servicio_ventas', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellidos', 'email', 'telefono']);
        });

        // Eliminar columnas de reserva_ventas
        Schema::table('reserva_ventas', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellidos', 'email', 'telefono']);
        });

        // Eliminar columnas de encargo_ventas
        Schema::table('encargo_ventas', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellidos', 'email', 'telefono']);
        });
    }

    public function down(): void
    {
        // Restaurar columnas en producto_ventas
        Schema::table('producto_ventas', function (Blueprint $table) {
            $table->string('nombre')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
        });

        // Restaurar columnas en servicio_ventas
        Schema::table('servicio_ventas', function (Blueprint $table) {
            $table->string('nombre')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
        });

        // Restaurar columnas en reserva_ventas
        Schema::table('reserva_ventas', function (Blueprint $table) {
            $table->string('nombre')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
        });

        // Restaurar columnas en encargo_ventas
        Schema::table('encargo_ventas', function (Blueprint $table) {
            $table->string('nombre')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
        });
    }
};
