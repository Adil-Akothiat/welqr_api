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
        Schema::create('default_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('visibility')->default(true);
            $table->string('availibility')->default('monday,tuesday,wednesday,thursday,friday,saturday,sunday');
            $table->string('filePath')->nullable()->default(null);
            $table->integer('order');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('default_menus');
    }
};
