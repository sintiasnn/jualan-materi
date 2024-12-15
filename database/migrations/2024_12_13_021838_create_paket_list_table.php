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

        Schema::create('paket_list', function (Blueprint $table) {
            $table->id();
            $table->string('image')->default('default.jpg'); // Package image
            $table->string('nama_paket'); // Package name

            $table->enum('audience', ["ukmppd", "aipki", "preklinik", "koas", "osce"]);
            $table->enum('tipe', ["class", "tryout","osce"]); // Package type
            $table->decimal('harga', 12, 0)->default(0); // Price in Rupiah
            $table->decimal('discount', 12, 0)->default(0); // Discount in Rupiah (New Column)

            $table->enum('tier', ["free", "paid"])->default("free"); // Free or paid tier
            $table->longText('deskripsi')->nullable(); // Longer description
            $table->boolean('active_status')->default(false); // Active or inactive

            $table->timestamps();

            // Indexes for performance
            $table->index(['tipe', 'active_status']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_list');
    }
};
