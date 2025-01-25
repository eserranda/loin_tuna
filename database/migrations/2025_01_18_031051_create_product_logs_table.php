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
        Schema::create('product_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ilc');
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id')->on('products')->cascadeOnUpdate()->cascadeOnDelete();
            $table->integer('no_loin');
            $table->decimal('berat', 5, 2);
            // $table->string('ekspor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_logs');
    }
};
