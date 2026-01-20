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
        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('color_id');
            $table->index('size');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->index('product_id');
            $table->index('color_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('coupon_id');
            $table->index('status');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_variant_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->index('slug');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['color_id']);
            $table->dropIndex(['size']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropIndex(['product_id']);
            $table->dropIndex(['color_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['coupon_id']);
            $table->dropIndex(['status']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_variant_id']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('collections', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->dropIndex(['code']);
        });
    }
};