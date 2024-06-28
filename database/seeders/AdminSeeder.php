<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'uperadmin@example.com',
            'password' => bcrypt('124mmmm'),
            'role' => 'super_admin',
            'ratio' => 10,
            'wallet' => 0,
        ]);
    }
}
