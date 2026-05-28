<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suscriptible_id');
            $table->string('suscriptible_type');
            $table->enum('tipo_periodo', ['dia', 'semana', 'mes', 'año'])->default('mes');
            $table->integer('cantidad_periodos')->default(1);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_proximo_pago');
            $table->enum('estado', ['activa', 'pausada', 'cancelada'])->default('activa');
            $table->timestamps();
            $table->index(['suscriptible_id', 'suscriptible_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};
