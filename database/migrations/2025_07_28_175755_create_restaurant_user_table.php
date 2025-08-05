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
        Schema::create('restaurant_user', function (Blueprint $table) {
            $table->id();
            $table->string('owner_email');
            $table->unsignedBigInteger('restaurant_id');
            $table->unsignedBigInteger('member_id');
            $table->foreign('restaurant_id')->references('id')->on('restaurant')->onDelete('cascade');
            $table->foreign('member_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
            $table->string('role')->default('member');
            $table->enum('permission', ['view', 'edit'])->default('view');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_user');
    }
};
