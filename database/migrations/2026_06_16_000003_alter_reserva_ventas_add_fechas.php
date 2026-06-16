<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reserva_ventas', function (Blueprint $table) {
            $table->dateTime('fecha_inicio')->nullable()->after('telefono');
            $table->dateTime('fecha_fin')->nullable()->after('fecha_inicio');
        });
    }

    public function down(): void
    {
        Schema::table('reserva_ventas', function (Blueprint $table) {
            $table->dropColumn(['fecha_inicio', 'fecha_fin']);
        });
    }
};
