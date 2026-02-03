<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Category FK
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
            
            // Brand FK (Perfume House)
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            
            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Pricing
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            
            // Visuals
            $table->string('image');
            $table->string('badge')->nullable();
            $table->string('badge_color')->nullable();
            
            // Perfume Attributes
            $table->string('concentration')->nullable(); // EDP, EDT
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex');
            $table->enum('type', ['original', 'local', 'vintage'])->default('original');
            $table->text('inspired_by')->nullable();
            
            // Self-referencing FK for "Inspired By" or specific links
            $table->unsignedBigInteger('original_product_id')->nullable();
            $table->foreign('original_product_id')->references('id')->on('products')->onDelete('set null');
            
            // Flags
            $table->boolean('featured')->default(false);
            $table->boolean('trending')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
