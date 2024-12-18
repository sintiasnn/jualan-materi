<?php

// database/migrations/xxxx_xx_xx_create_active_sessions_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('active_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token')->unique();
            $table->string('device_name')->nullable();
            $table->timestamp('last_active_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('active_sessions');
    }
};