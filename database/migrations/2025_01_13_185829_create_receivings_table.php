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
        Schema::create('receivings', function (Blueprint $table) {
            $table->id();
            $table->string('ilc');
            $table->unsignedBigInteger('id_supplier')->nullable();
            $table->foreign('id_supplier')->references('id')->on('suppliers')->cascadeOnUpdate()->nullOnDelete();
            $table->date('tanggal');
            $table->string('inspection')->nullable(); // inspection report
            $table->boolean('is_used')->default(false); // is_used column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receivings');
    }
};
