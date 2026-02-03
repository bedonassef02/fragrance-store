<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('capacity')->nullable()->after('size'); // 100, 50
            $table->string('unit')->default('ml')->after('capacity');
            $table->string('container_type')->default('Original Bottle')->after('unit'); // Bottle, Decant, Vial
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'unit', 'container_type']);
        });
    }
};
