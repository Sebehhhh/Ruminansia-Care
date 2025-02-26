<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat user admin default
        User::create([
            'name'     => 'Administrator',
            'email'    => 'semab@ruminansia-care.com',
            'password' => Hash::make('password'), // Ganti 'password' dengan password yang diinginkan
        ]);

        // Jika ingin menambahkan user dummy lainnya, bisa menggunakan factory (jika sudah ada)
        // User::factory()->count(10)->create();
    }
}