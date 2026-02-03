<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['top', 'heart', 'base', 'general'])->default('general');
            $table->string('image')->nullable(); // Just in case we want icons
            $table->timestamps();
        });

        Schema::create('product_note', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('note_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['top', 'heart', 'base'])->default('top');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_note');
        Schema::dropIfExists('notes');
    }
};
