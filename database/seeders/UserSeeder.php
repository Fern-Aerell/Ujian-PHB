<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'type' => 'admin',
                'name' => 'Admin',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Crypt::encryptString('admin#1234'),
            ],
            [
                'type' => 'murid',
                'name' => 'Fern Aerell',
                'username' => 'aerell',
                'email' => 'aerell@gmail.com',
                'email_verified_at' => now(),
                'password' => Crypt::encryptString('12345678'),
            ],
            [
                'type' => 'guru',
                'name' => 'Reski Ramadhan',
                'username' => 'reski',
                'email' => 'reski@gmail.com',
                'email_verified_at' => now(),
                'password' => Crypt::encryptString('12345678'),
            ]
        ];

        foreach($userData as $user) {
            User::create($user);
        }
    }
}
