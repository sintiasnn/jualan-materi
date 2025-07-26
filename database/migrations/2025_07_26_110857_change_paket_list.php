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
        Schema::table('paket_list', function (Blueprint $table) {
            $table->dropColumn('audience');
            $table->dropColumn('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paket_list', function (Blueprint $table) {
            $table->enum('audience', ["ukmppd", "aipki", "preklinik", "koas", "osce"]);
            $table->enum('tipe', ["class", "tryout","osce"]); // Package type
        });
    }
};
