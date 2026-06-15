<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('encargos', function (Blueprint $table) {
            $table->dropColumn(['cliente', 'email', 'telefono', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::table('encargos', function (Blueprint $table) {
            $table->string('cliente')->after('nombre');
            $table->string('email')->after('cliente');
            $table->string('telefono')->after('email');
            $table->timestamp('fecha')->nullable()->after('precio');
        });
    }
};
