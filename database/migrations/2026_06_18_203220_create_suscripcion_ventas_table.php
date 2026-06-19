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
        Schema::create('suscripcion_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained()->onDelete('cascade');
            $table->foreignId('suscripcion_id')->constrained('suscripciones')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_proximo_pago');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripcion_ventas');
    }
};
