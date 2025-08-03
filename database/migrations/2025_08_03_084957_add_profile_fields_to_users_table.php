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
        Schema::table('users', function (Blueprint $table) {
            // Add profile fields
            $table->string('username')->unique()->nullable()->after('name');
            $table->enum('account_type', ['personal', 'organization'])->default('personal')->after('email');
            $table->string('profile_picture')->nullable()->after('account_type');
            $table->text('bio')->nullable()->after('profile_picture');
            $table->string('location')->nullable()->after('bio');
            $table->string('website')->nullable()->after('location');
            $table->timestamp('last_active_at')->nullable()->after('website');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'account_type', 
                'profile_picture',
                'bio',
                'location',
                'website',
                'last_active_at'
            ]);
        });
    }
};