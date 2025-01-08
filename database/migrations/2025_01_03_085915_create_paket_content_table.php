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
        Schema::create('paket_content', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paket_id');
            $table->foreign('paket_id')->references('id')->on('paket_list')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('content_id');
            $table->foreign('content_id')->references('id')->on('class_content')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('activation_date');
            $table->dateTime('expired_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_content');
    }
};
