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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('likeable'); // This creates likeable_type and likeable_id columns AND their index
            $table->timestamps();
            
            // Ensure a user can only like something once
            $table->unique(['user_id', 'likeable_type', 'likeable_id']);
            
            // Note: morphs() already creates an index for likeable_type and likeable_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};