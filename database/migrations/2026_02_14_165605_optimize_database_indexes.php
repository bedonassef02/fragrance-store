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
        // optimize products table
        Schema::table('products', function (Blueprint $table) {
            $table->index('price');
            $table->index(['gender', 'price']); // Composite for filtering
            $table->index(['brand_id', 'created_at']); // Brand pages
            $table->index('featured');
            $table->index('concentration'); // For unique concentration filter
        });

        // optimize orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->index('created_at');
            $table->index('payment_status'); // Critical for cleanup job
            $table->index(['user_id', 'created_at']); // Customer history
        });

        // optimize variants
        Schema::table('product_variants', function (Blueprint $table) {
            $table->index('quantity');
            $table->index('capacity'); // For calculating unique capacities
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['price']);
            $table->dropIndex(['gender', 'price']);
            $table->dropIndex(['brand_id', 'created_at']);
            $table->dropIndex(['featured']);
            $table->dropIndex(['concentration']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['user_id', 'created_at']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['quantity']);
            $table->dropIndex(['capacity']);
        });
    }
};
