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

        Schema::create('redeem_codes', function (Blueprint $table) {
            $table->id();

            // Redeem code details
            $table->enum('tipe', ["class", "tryout", "paket"])->default('paket');
            $table->string('code')->unique();

            // Link to package, class, or tryout
            $table->unsignedBigInteger('related_id')->nullable(); 
            $table->string('related_type')->nullable(); // Polymorphic for flexibility

            // Discount amount (in case it's a discount code)
            $table->decimal('discount_amount', 15, 2)->nullable(); // Discount in currency amount (e.g. 50000)

            // Code status
            $table->boolean('activation_status')->default(false);

            // Expiry and redemption tracking
            $table->dateTime('expiry_date')->nullable();
            $table->unsignedBigInteger('redeemed_by')->nullable();
            $table->foreign('redeemed_by')->references('id')->on('users')->onDelete('set null');
            $table->dateTime('redeemed_at')->nullable();

            $table->timestamps();

            // Indexing
            $table->index(['code', 'activation_status']);
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redeem_codes');
    }
};
