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
            'name'     => 'seman',
            'email'    =>  'seman@gmail.com',
            'is_admin' => true,
            'password' => Hash::make('password'), // password default: "password"
        ]);
    }
}
