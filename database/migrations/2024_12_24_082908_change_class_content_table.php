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
        Schema::table('class_content', function (Blueprint $table){
            $table->string('kode_submateri', 255)->nullable();
            $table->string('nama_submateri', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_content', function (Blueprint $table){
            $table->dropColumn('kode_submateri');
            $table->dropColumn('nama_submateri');
        });
    }
};
