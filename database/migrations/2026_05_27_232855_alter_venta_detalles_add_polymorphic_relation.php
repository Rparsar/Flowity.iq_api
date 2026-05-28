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
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->dropForeign(['producto_id']);
            $table->dropColumn('producto_id');
            $table->unsignedBigInteger('vendible_id')->nullable();
            $table->string('vendible_type')->nullable();
            $table->index(['vendible_id', 'vendible_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venta_detalles', function (Blueprint $table) {
            $table->dropIndex(['vendible_id', 'vendible_type']);
            $table->dropColumn(['vendible_id', 'vendible_type']);
            $table->foreignId('producto_id')->nullable()->constrained('productos')->nullOnDelete();
        });
    }
};
