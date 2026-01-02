<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
            'status' => 'approved',
        ]);

        User::create([
            'name' => 'Caterer',
            'email' => 'caterer@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'caterer',
            'status' => 'approved',
        ]);

        User::create([
            'name' => 'Customer',
            'email' => 'customer@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'customer',
            'status' => 'approved',
        ]);
    }
}
