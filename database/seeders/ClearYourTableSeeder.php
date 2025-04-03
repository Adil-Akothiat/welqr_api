<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearYourTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('restaurant')->delete();
        DB::table('qrcode')->delete();
        DB::table('languages')->delete();
        DB::table('opening_times')->delete();
        DB::table('social_networks')->delete();
        DB::table('wifi')->delete();
    }
}
