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
    public function run(): void
    {
        User::create([
            'firstname' => 'welqradminf',
            'lastname' => 'welqradminl',
            'email' => 'welqrapp2025@admin.com',
            'photo'=> null,
            'role'=> 'admin',
            'account_confirmation'=> true,
            'password' => Hash::make('welqradmin@123'),
        ]);
    }
}
