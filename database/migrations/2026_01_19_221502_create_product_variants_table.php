<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            
            // Capacity / Size Logic for Perfumes
            $table->integer('capacity')->nullable(); // e.g. 100, 50, 10
            $table->string('unit')->default('ml');
            $table->string('container_type')->default('Original Bottle'); // Bottle, Decant
            $table->decimal('price', 10, 2)->nullable(); // Specific price for this variant
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->index('quantity');
            $table->index('capacity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
