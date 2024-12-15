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

        Schema::create('tryouts', function (Blueprint $table) {
            $table->id();
            $table->enum('tipe', ["general","postest","pretest"]);
            $table->bigInteger('time_limit');
            $table->bigInteger('passing_grade');
            $table->bigInteger('soal_count');
            $table->longText('pembahasan_url')->nullable();
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
        Schema::dropIfExists('tryouts');
    }
};
