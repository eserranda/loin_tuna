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
        Schema::create('forward_traceabilities', function (Blueprint $table) {
            $table->id();
            $table->string('ilc');
            $table->date('tanggal_receiving')->nullable();
            $table->date('tanggal_cutting')->nullable();
            $table->date('tanggal_retouching')->nullable();
            $table->date('tanggal_packing')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forward_traceabilities');
    }
};
