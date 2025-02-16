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
        Schema::create('packing_pos', function (Blueprint $table) {
            $table->id();
            $table->string('po_number');
            $table->unsignedBigInteger('id_product');
            $table->foreign('id_product')->references('id')->on('products')->onDelete('cascade');
            $table->integer('total_qty');
            $table->float('total_weight');
            $table->integer('progress');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packing_pos');
    }
};
