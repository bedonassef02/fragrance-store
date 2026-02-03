<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('brand_id')->nullable()->after('category_id')->constrained()->onDelete('set null');
            $table->string('concentration')->nullable()->after('name'); // EDP, EDT
            $table->enum('gender', ['male', 'female', 'unisex'])->default('unisex')->after('name');
            $table->enum('type', ['original', 'local', 'vintage'])->default('original')->after('name');
            $table->text('inspired_by')->nullable()->after('description');
            
            // Self-referencing FK must be added carefully
             $table->unsignedBigInteger('original_product_id')->nullable()->after('id');
             $table->foreign('original_product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['original_product_id']);
            $table->dropColumn(['brand_id', 'concentration', 'gender', 'type', 'inspired_by', 'original_product_id']);
        });
    }
};
