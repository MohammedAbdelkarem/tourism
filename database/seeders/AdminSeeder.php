<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seedda
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'admin_name',
            'role' => 'super_admin',
            'password' => '12345678',
        ]);
    }
}
