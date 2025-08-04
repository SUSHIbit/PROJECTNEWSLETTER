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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade');
            
            // Use morphs() which automatically creates the index - don't add manual index
            $table->morphs('reportable'); // This creates reportable_type, reportable_id AND the index
            
            $table->enum('reason', ['spam', 'inappropriate', 'harassment', 'fake_news', 'copyright', 'other']);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->text('admin_notes')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            
            // Add only the additional indexes you need (morphs() already creates the main one)
            $table->index(['status', 'created_at']);
            // Remove this line: $table->index(['reportable_type', 'reportable_id']); 
            // because morphs() already creates it
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};