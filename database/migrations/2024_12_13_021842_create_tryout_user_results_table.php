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

        Schema::create('tryout_user_results', function (Blueprint $table) {
            $table->id();

            // Foreign key for user
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Foreign key for tryout
            $table->unsignedBigInteger('tryout_id');
            $table->foreign('tryout_id')->references('id')->on('tryouts')->onDelete('cascade');

            // Foreign key for the specific attempt
            $table->unsignedBigInteger('tryout_attempt_id');
            $table->foreign('tryout_attempt_id')->references('id')->on('tryout_user_attempts')->onDelete('cascade');

            // Final score
            $table->unsignedInteger('final_grade')->default(0);

            // Pass/fail indicator
            $table->boolean('is_passed')->default(false);

            // User feedback
            $table->longText('user_feedback')->nullable();

            $table->timestamps();

            // Indexes for query optimization
            $table->index(['user_id', 'tryout_id', 'is_passed']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_user_results');
    }
};
