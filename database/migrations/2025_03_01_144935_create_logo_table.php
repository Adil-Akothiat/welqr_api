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
        Schema::create('logo', function (Blueprint $table) {
            $table->id();
            $table->boolean('demo')->default(true);
            $table->float('size')->default(0.4);
            $table->boolean('hideCenter')->default(true);
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
        Schema::dropIfExists('logo');
    }
};
