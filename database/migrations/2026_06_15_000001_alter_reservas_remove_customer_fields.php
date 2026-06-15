<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropColumn(['cliente', 'email', 'telefono', 'fecha_inicio', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::table('reservas', function (Blueprint $table) {
            $table->string('cliente')->after('nombre');
            $table->string('email')->after('cliente');
            $table->string('telefono')->after('email');
            $table->timestamp('fecha_inicio')->nullable()->after('precio');
            $table->timestamp('fecha_fin')->nullable()->after('fecha_inicio');
        });
    }
};
