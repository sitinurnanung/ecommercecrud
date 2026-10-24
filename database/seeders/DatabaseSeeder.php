<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@techcatalog.com',
            'password' => Hash::make('admin@!12102009'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Siti Nur Hayati',
            'email' => 'siti@example.com',
            'password' => Hash::make('admin@!12102009'),
            'role' => 'user',
        ]);
    }
}