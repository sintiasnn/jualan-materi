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

        Schema::table('paket_content', function (Blueprint $table) {
            $table->dropForeign(['content_id']);
            $table->renameColumn('content_id', 'materi_id');
            $table->foreign('materi_id')->references('id')->on('materi')->onDelete('cascade')->onUpdate('cascade');
            $table->dropColumn('activation_date');
            $table->dropColumn('expired_date');
        });

        Schema::rename('paket_content', 'paket_materi');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_materi', function (Blueprint $table) {
            $table->renameColumn('materi_id', 'content_id');
            $table->dropForeign('paket_materi_materi_id_foreign');
            $table->foreign('content_id')->references('id')->on('class_content')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('activation_date')->nullable();
            $table->dateTime('expired_date')->nullable();
        });

        Schema::rename('paket_materi', 'paket_content');
    }
};
