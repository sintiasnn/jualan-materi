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
        Schema::disableForeignKeyConstraints();

        Schema::create('tryout_user_attempts', function (Blueprint $table) {
            $table->id();
            
            // Foreign key for tryout
            $table->unsignedBigInteger('tryout_id');
            $table->foreign('tryout_id')->references('id')->on('tryouts')->onDelete('cascade');

            // Foreign key for user
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // User's answers
            $table->longText('ans_json')->nullable();

            // Attempt timing
            $table->dateTime('attempt_start');
            $table->dateTime('attempt_end')->nullable();

            // New: Duration in seconds
            $table->unsignedInteger('duration_taken')->nullable(); 

            // Time left (optional)
            $table->bigInteger('time_left')->nullable();

            // Status of the attempt
            $table->enum('status', ["paused", "completed", "ongoing"])->default("ongoing");

            $table->timestamps();

            // Indexes for faster queries
            $table->index(['user_id', 'tryout_id', 'status']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_user_attempts');
    }
};
