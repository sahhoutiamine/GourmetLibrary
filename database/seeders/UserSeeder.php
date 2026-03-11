<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Chef Bibliothécaire',
            'email' => 'admin@gourmet.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::create([
            'name' => 'Amine Gourmand',
            'email' => 'reader@gourmet.com',
            'password' => Hash::make('password'),
            'role' => 'reader'
        ]);
    }
}
