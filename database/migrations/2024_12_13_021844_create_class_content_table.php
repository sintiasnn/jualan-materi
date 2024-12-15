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

        Schema::create('class_content', function (Blueprint $table) {
            $table->id();

            // Foreign key for class
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');

            // Foreign key for bidang (nullable)
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->foreign('bidang_id')->references('id')->on('ref_bidang_list')->onDelete('set null');

            // Content paths
            $table->string('video_url')->nullable(); // For video files
            $table->string('file_url')->nullable();  // For PDFs or other files

            // Content type: video, pdf, or link
            $table->enum('type', ['video', 'pdf', 'link'])->default('pdf');

            // Description
            $table->longText('deskripsi')->nullable();

            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_content');
    }
};
