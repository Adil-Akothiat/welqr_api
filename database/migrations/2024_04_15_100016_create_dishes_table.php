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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->longText('name');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('price')->nullable();
            $table->longText('prices')->nullable();
            $table->longText('allergens')->nullable();
            $table->longText('tags')->nullable();
            $table->boolean('visibility')->default(true);
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
