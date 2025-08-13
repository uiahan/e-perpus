<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@email.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
        
        User::create([
            'name' => 'superadmin',
            'email' => 'superadmin@email.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
        ]);
    }
}
