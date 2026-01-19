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
        Schema::rename('product_sizes', 'product_variants');
        Schema::table('product_variants', function (Blueprint $table) {
            $table->foreignId('color_id')->nullable()->constrained('colors')->nullOnDelete();
            $table->integer('quantity')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropForeign(['color_id']);
            $table->dropColumn(['color_id', 'quantity']);
        });
        Schema::rename('product_variants', 'product_sizes');
    }
};
