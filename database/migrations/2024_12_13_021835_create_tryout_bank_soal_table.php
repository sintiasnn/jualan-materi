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

        Schema::create('tryout_bank_soal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tryout_id')->nullable();
            $table->foreign('tryout_id')->references('id')->on('tryouts');
            $table->unsignedBigInteger('bidang_id');
            $table->foreign('bidang_id')->references('id')->on('ref_bidang_list');
            $table->longText('soal_image')->nullable();
            $table->longText('soal_content');
            $table->longText('ops_a');
            $table->longText('reasoning_a')->nullable();
            $table->longText('ops_b');
            $table->longText('reasoning_b')->nullable();
            $table->longText('ops_c');
            $table->longText('reasoning_c')->nullable();
            $table->longText('ops_d');
            $table->longText('reasoning_d')->nullable();
            $table->longText('ops_e');
            $table->longText('reasoning_e')->nullable();
            $table->string('true_ans');
            $table->longText('pembahasan_url')->nullable();
            $table->unsignedBigInteger('tutor_id');
            $table->foreign('tutor_id')->references('id')->on('users');
            $table->tinyInteger('approval')->default(0);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tryout_bank_soal');
    }
};
