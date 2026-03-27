<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminModel;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        AdminModel::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@alphandt.com',
            'password' => 'alphandt@vungtau2002',
            'role_id' => 1,
        ]);
    }
}
