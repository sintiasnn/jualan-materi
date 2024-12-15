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

        Schema::create('transaksi_user', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('paket_id');
            $table->foreign('paket_id')->references('id')->on('paket_list')->onDelete('cascade');

            $table->unsignedBigInteger('total_amount')->default(0);
            $table->string('snap_token')->nullable(); // MidTrans Snap token
            $table->string('redirect_url')->nullable();

            $table->dateTime('tanggal_pembelian');
            $table->dateTime('gateway_waktu_pembayaran')->nullable();
            $table->dateTime('waktu_expired')->nullable();

            $table->string('gateway_fraud_status')->nullable();
            $table->string('gateway_payment_method')->nullable(); // Payment method like VA, credit card, etc.
            $table->string('gateway_transaction_id')->nullable();
            $table->string('gateway_status_message')->nullable();
            

            $table->unsignedBigInteger('redeem_code_id')->nullable();
            $table->foreign('redeem_code_id')->references('id')->on('redeem_codes')->onDelete('set null');

            $table->enum('status', ["success", "pending", "failed", "cancelled"]);
            $table->timestamps();

            // Indexes for faster lookups
            $table->index(['user_id', 'paket_id', 'status']);

            // Unique constraint to prevent duplicate ownership
            $table->unique(['user_id', 'paket_id', 'status'], 'unique_user_paket_success');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_user');
    }
};
