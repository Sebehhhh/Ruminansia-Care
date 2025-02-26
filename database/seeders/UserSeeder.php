<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat 9 dummy user
        for ($i = 1; $i <= 9; $i++) {
            User::create([
                'name'     => 'Dummy User ' . $i,
                'email'    => 'dummy' . $i . '@example.com',
                'password' => Hash::make('password'), // password default: "password"
            ]);
        }
    }
}