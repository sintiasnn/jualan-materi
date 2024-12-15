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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('gender', ["laki", "perempuan"])->nullable();
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('phone_number')->unique();
            $table->string('avatar')->default('default.jpg');
            $table->string('password');

            $table->enum('role', ["admin", "tutor", "user"])->default('user');

            // Nullable Universitas ID
            $table->unsignedBigInteger('universitas_id')->nullable();
            $table->foreign('universitas_id')->references('id')->on('ref_universitas_list')->onDelete('set null');

            // Referral Relationships
            $table->string('referral_code')->unique();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->foreign('referred_by')->references('id')->on('users')->onDelete('set null');

            // Notifications
            $table->boolean('email_notification')->default(false);
            $table->boolean('wa_notification')->default(false);

            // Active Status
            $table->boolean('active_status')->default(true);

            // Token for "remember me"
            $table->string('remember_token')->nullable();

            // Soft deletes and timestamps
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
