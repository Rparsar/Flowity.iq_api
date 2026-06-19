<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Índice compuesto para queries de estadísticas por estado y fecha
            $table->index(['estado', 'fecha'], 'idx_ventas_estado_fecha');
            
            // Índice individual para fecha (usado en whereBetween)
            $table->index('fecha', 'idx_ventas_fecha');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropIndex('idx_ventas_estado_fecha');
            $table->dropIndex('idx_ventas_fecha');
        });
    }
};
