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
            // Drop the unique index on email
            $table->dropUnique(['email']);
            
            // Drop email-related columns
            $table->dropColumn(['email', 'email_verified_at']);
            
            // Add username column
            $table->string('username')->unique()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the username column
            $table->dropColumn('username');
            
            // Add back email columns
            $table->string('email')->after('name');
            $table->timestamp('email_verified_at')->nullable();
            
            // Add back the unique index
            $table->unique('email');
        });
    }
};
