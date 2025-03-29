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
        Schema::create('restaurant', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('coverImage');
            $table->string('description')->default('');
            $table->string('currency')->default('usd');
            $table->enum('mode', ['light', 'dark'])->default('light');
            $table->foreignId('user_id');
            $table->foreignId('qrcode_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('qrcode_id')->references('id')->on('qrcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant');
    }
};
