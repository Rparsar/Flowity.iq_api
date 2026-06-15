<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->dropColumn(['fecha_inicio', 'fecha_proximo_pago']);
        });
    }

    public function down(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->timestamp('fecha_inicio')->nullable()->after('cantidad_periodos');
            $table->timestamp('fecha_proximo_pago')->nullable()->after('fecha_inicio');
        });
    }
};
