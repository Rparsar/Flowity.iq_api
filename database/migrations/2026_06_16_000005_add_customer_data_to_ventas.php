<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->string('nombre')->nullable()->after('cliente');
            $table->string('apellidos')->nullable()->after('nombre');
            $table->string('email')->nullable()->after('apellidos');
            $table->string('telefono')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'apellidos', 'email', 'telefono']);
        });
    }
};
