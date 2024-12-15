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

        Schema::create('class_paket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
            $table->unsignedBigInteger('paket_id');
            $table->foreign('paket_id')->references('id')->on('paket_list')->onDelete('cascade');
            
            // Additional metadata fields
            $table->dateTime('activation_date')->nullable()->comment('Date when the class becomes active');
            $table->dateTime('expiration_date')->nullable()->comment('Date when access to the class expires');
            $table->integer('order')->nullable()->comment('Order of the class in the package');
            
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_paket');
    }
};
