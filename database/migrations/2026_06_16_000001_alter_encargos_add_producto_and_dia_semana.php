<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('encargos', function (Blueprint $table) {
            $table->foreignId('producto_id')->nullable()->constrained('productos')->cascadeOnDelete();
            $table->enum('dia_semana', ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('encargos', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['producto_id']);
            $table->dropColumn(['producto_id', 'dia_semana']);
        });
    }
};
