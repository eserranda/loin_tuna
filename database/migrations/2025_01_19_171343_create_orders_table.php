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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('po_number');
            $table->integer('total_price')->nullable();
            $table->string('phone')->nullable();
            $table->string('negara')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('kecamatan')->nullable();
            $table->text('jalan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('bank')->nullable();
            $table->string('nama')->nullable();
            $table->string('receipt_image')->nullable();
            // $table->string('payment_proof')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('status');
            $table->boolean('is_packed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
