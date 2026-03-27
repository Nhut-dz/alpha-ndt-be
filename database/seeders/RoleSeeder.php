<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tblroles')->insert([
            ['name' => 'Super Admin'],
            ['name' => 'Post_Admin'],
            ['name' => 'HR_Admin'],
        ]);
    }
}
