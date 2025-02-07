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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->string('ilc');
            $table->string('stage');
            $table->integer('uji_lab')->nullable();
            $table->integer('tekstur')->nullable();
            $table->integer('bau')->nullable();
            $table->integer('es')->nullable();
            $table->integer('suhu')->nullable();
            $table->integer('keterangan')->nullable();
            $table->integer('hasil')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
