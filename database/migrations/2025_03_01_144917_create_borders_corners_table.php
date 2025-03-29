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
        Schema::create('borders_corners', function (Blueprint $table) {
            $table->id();
            $table->enum('dotStyle', ['square', 'rouded', 'dots', 'classy', 'classy-rounded', 'extra-rounded']);
            $table->enum('cornerSquareStyles', ['square', 'dot', 'extra-rounded']);
            $table->enum('cornerDotStyles', ['square', 'dot']);
            $table->foreignId('qrcode_id');
            $table->foreign('qrcode_id')->references('id')->on('qrcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borders_corners');
    }
};
