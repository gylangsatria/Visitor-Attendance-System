<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@visify.com',
            'password' => Hash::make('password123'),
            'access_level' => 1,
            'phone' => '081234567890',
            'address' => 'Admin Office, Jakarta'
        ]);
        
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@visify.com',
            'password' => Hash::make('password123'),
            'access_level' => 2,
            'phone' => '081234567891',
            'address' => 'Editor Office, Bandung'
        ]);
        
        User::create([
            'name' => 'Viewer User',
            'email' => 'viewer@visify.com',
            'password' => Hash::make('password123'),
            'access_level' => 3,
            'phone' => '081234567892',
            'address' => 'Viewer Office, Surabaya'
        ]);

        User::create([
            'name' => 'Guest User',
            'email' => 'guest@visify.com',
            'password' => Hash::make('password123'),
            'access_level' => 4,
            'phone' => '081234567893',
            'address' => 'Guest Area, Yogyakarta'
        ]);
    }
}