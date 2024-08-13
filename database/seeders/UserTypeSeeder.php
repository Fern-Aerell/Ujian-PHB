<?php

namespace Database\Seeders;

use App\Models\UserType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeData = [
            'admin',
            'guru',
            'murid'
        ];

        foreach($typeData as $type) {
            $typeModel = new UserType;
            $typeModel->type = $type;
            $typeModel->save();
        }
    }
}
