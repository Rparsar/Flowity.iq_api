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
        Schema::table('suscripciones', function (Blueprint $table) {
            // Eliminar campos polimórficos
            $table->dropColumn(['suscriptible_id', 'suscriptible_type', 'tipo_periodo', 'cantidad_periodos']);
            
            // Añadir campos nuevos
            $table->string('nombre')->after('id');
            $table->text('descripcion')->nullable()->after('nombre');
            $table->decimal('precio', 10, 2)->after('descripcion');
            $table->foreignId('producto_id')->nullable()->after('precio')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            // Restaurar campos polimórficos
            $table->unsignedBigInteger('suscriptible_id')->after('id');
            $table->string('suscriptible_type')->after('suscriptible_id');
            $table->enum('tipo_periodo', ['dia', 'semana', 'mes', 'año'])->default('mes')->after('suscriptible_type');
            $table->integer('cantidad_periodos')->default(1)->after('tipo_periodo');
            
            // Eliminar campos nuevos
            $table->dropColumn(['nombre', 'descripcion', 'precio', 'producto_id']);
        });
    }
};
