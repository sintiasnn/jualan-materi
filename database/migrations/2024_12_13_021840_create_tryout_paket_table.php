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

        Schema::create('tryout_paket', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('paket_id');
            $table->foreign('paket_id')->references('id')->on('paket_list')->onDelete('cascade');
            $table->unsignedBigInteger('tryout_id');
            $table->foreign('tryout_id')->references('id')->on('tryouts')->onDelete('cascade');

            // Additional metadata fields
            $table->dateTime('activation_date')->nullable()->comment('Date when the tryout becomes active');
            $table->dateTime('expiration_date')->nullable()->comment('Date when access to the tryout expires');
            $table->integer('order')->nullable()->comment('Order of the tryout in the package');

            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_paket');
    }
};
