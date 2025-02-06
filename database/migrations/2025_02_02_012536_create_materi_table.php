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

        Schema::create('materi', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('subdomain_id');
            $table->foreign('subdomain_id')->references('id')->on('subdomain')->onUpdate('cascade')->onDelete('cascade');

            $table->string('kode_materi')->unique();
            $table->string('nama_materi', length: 255);
            $table->integer('tingkat_kesulitan');
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
        Schema::dropIfExists('materi');
    }
};
