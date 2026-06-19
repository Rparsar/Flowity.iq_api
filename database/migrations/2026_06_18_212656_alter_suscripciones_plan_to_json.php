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
            // Cambiar de enum a JSON
            $table->dropColumn('plan');
            $table->json('planes')->default('["mensual"]')->after('precio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->dropColumn('planes');
            $table->enum('plan', ['mensual', 'trimestral', 'semestral'])->default('mensual')->after('precio');
        });
    }
};
