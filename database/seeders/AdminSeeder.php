<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // php artisan db:seed --class=AdminSeeder
    public function run(): void
    {
        User::create([
            'firstname' => env('ADMINFNAME'),
            'lastname' => env('ADMINLNAME'),
            'email' => env('ADMINEMAIL'),
            'photo'=> null,
            'role'=> 'admin',
            'account_confirmation'=> true,
            'password' => Hash::make(env('ADMINPASSWORD')),
        ]);
    }
}
