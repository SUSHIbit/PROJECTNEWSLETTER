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
        Schema::table('posts', function (Blueprint $table) {
            // Add fulltext index for better search performance
            $table->fulltext(['title', 'content']);
        });

        Schema::table('users', function (Blueprint $table) {
            // Add index for user search
            $table->index(['name', 'username']);
        });

        Schema::table('organizations', function (Blueprint $table) {
            // Add index for organization search
            $table->index(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropFulltext(['title', 'content']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['name', 'username']);
        });

        Schema::table('organizations', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};