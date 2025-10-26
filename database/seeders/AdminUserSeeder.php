<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        \App\Models\User::create([
            'name' => 'Administrator',
            'email' => 'admin@presensia.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'employee_id' => 'ADM001',
            'is_active' => true,
        ]);

        // Create Manager User
        \App\Models\User::create([
            'name' => 'Manager User',
            'email' => 'manager@presensia.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'employee_id' => 'MGR001',
            'is_active' => true,
        ]);

        // Create Employee User
        \App\Models\User::create([
            'name' => 'Employee User',
            'email' => 'employee@presensia.com',
            'password' => bcrypt('password'),
            'role' => 'employee',
            'employee_id' => 'EMP001',
            'is_active' => true,
        ]);
    }
}
