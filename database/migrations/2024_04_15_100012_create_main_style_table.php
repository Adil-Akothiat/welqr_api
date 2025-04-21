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
        Schema::create('main_style', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('backgroundColor')->default('#fffff');
            $table->string('qrcodeColor')->default('#00000');
            $table->enum('correctionLevel', ['L','M','Q','H']);
            $table->unsignedBigInteger('qrcode_id');
            $table->foreign('qrcode_id')->references('id')->on('qrcode')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_style');
    }
};
