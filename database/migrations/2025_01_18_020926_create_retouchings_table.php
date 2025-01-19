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
        Schema::create('retouchings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->unsignedBigInteger('id_supplier')->nullable();
            $table->foreign('id_supplier')->references('id')->on('suppliers')->cascadeOnUpdate()->nullOnDelete();
            $table->string('ilc');
            $table->string('ilc_cutting');
            $table->integer('no_loin');
            $table->decimal('berat', 5, 2);
            $table->string('ekspor');
            $table->string('inspection')->nullable(); // inspection report
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('retouchings');
    }
};
