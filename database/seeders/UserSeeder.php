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
        $userData = [];

        foreach($userData as $user) {
            $userModel = new User;
            $userModel->type = $user['type'];
            $userModel->name = $user['name'];
            $userModel->username = $user['username'];
            $userModel->password = $user['password'];
            $userModel->save();
        }
    }
}
