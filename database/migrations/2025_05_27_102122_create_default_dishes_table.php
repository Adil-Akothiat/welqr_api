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
        Schema::create('default_dishes', function (Blueprint $table) {
            $table->id();
            $table->longText('name');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('price')->nullable();
            $table->longText('prices')->nullable();
            $table->longText('allergens')->nullable();
            $table->longText('tags')->nullable();
            $table->boolean('visibility')->default(true);
            $table->unsignedBigInteger('default_menu_id');
            $table->foreign('default_menu_id')->references('id')->on('default_menus')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_dishes');
    }
};
