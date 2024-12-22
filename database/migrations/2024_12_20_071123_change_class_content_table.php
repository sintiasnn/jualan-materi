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
            $table->unsignedBigInteger('guru_id')->nullable();
            $table->integer('subdomain_id')->nullable();
            $table->string('kode_materi',5)->nullable();
            $table->string('nama_materi',255)->nullable();
            $table->foreign('guru_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('file_url')->nullable()->change();
            $table->longText('deskripsi')->change();
            $table->unsignedBigInteger('bidang_id')->nullable()->change();
            $table->unsignedBigInteger('class_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_content', function (Blueprint $table){
            $table->dropForeign('class_content_guru_id_foreign');
            $table->dropColumn('guru_id');
            $table->dropColumn('subdomain_id');
            $table->dropColumn('kode_materi');
            $table->dropColumn('nama_materi');
        });
    }
};
