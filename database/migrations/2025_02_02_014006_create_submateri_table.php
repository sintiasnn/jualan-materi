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

        Schema::create('submateri', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('materi_id');
            $table->foreign('materi_id')->references('id')->on('materi')->onUpdate('cascade')->onDelete('cascade');

            $table->string('kode_submateri')->unique();
            $table->string('nama_submateri', length: 255);
            $table->longText('deskripsi')->nullable();
            $table->string('reference')->nullable();
            $table->string('video_url')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submateri');
    }
};
