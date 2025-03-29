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
        Schema::create('opening_times', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->enum('openStatus', ['open', 'closed'])->default('open');
            $table->foreignId('restaurant_id');
            $table->foreign('restaurant_id')->references('id')->on('restaurant');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opening_times');
    }
};
