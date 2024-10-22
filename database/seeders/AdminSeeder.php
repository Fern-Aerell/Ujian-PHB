<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminData = [
            [
                'user_id' => 1
            ]
        ];
        foreach($adminData as $admin)
        {
            Admin::create($admin);
        }
    }
}
