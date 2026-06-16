<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('encargo_ventas', function (Blueprint $table) {
            $table->integer('cantidad')->default(1)->after('telefono');
        });
    }

    public function down(): void
    {
        Schema::table('encargo_ventas', function (Blueprint $table) {
            $table->dropColumn('cantidad');
        });
    }
};
