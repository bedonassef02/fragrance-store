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
        Schema::table('order_items', function (Blueprint $table) {
            // Add the new product_variant_id column
            $table->foreignId('product_variant_id')->after('order_id')->constrained('product_variants');

            // Drop the old columns
            $table->dropColumn(['product_id', 'size', 'color']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the foreign key and the column
            $table->dropForeign(['product_variant_id']);
            $table->dropColumn('product_variant_id');

            // Re-add the old columns
            $table->foreignId('product_id')->constrained('products');
            $table->string('size');
            $table->string('color')->nullable();
        });
    }
};