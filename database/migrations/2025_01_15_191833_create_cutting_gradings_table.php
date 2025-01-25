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
        Schema::create('cutting_gradings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_supplier')->nullable();
            $table->foreign('id_supplier')->references('id')->on('suppliers')->cascadeOnUpdate()->nullOnDelete();
            $table->string('ilc');
            $table->string('ilc_cutting');
            $table->decimal('berat', 5, 2);
            $table->decimal('sisa_berat', 5, 2)->nullable();
            $table->integer('no_loin');
            $table->string('grade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutting_gradings');
    }
};
