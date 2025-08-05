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
        Schema::table('restaurant', function(Blueprint $table) {
            if(!Schema::hasColumn('restaurant', 'isActive')):
                $table->boolean('isActive')->default(false)->after('mode');
            endif;
             if(!Schema::hasColumn('restaurant', 'visible')):
                $table->boolean('visible')->default(true)->after('isActive');
            endif;
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(Schema::hasColumn('restaurant', 'visible')):
            Schema::table('restaurant', function(Blueprint $table) {
                $table->dropColumn('visible');
            });
            Schema::table('restaurant', function(Blueprint $table) {
                $table->dropColumn('isActive');
            });
        endif;
    }
};
