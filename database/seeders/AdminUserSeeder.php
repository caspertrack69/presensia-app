<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@presensia.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'employee_id' => 'ADM001',
                'phone' => '081200000001',
                'department_id' => null,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@presensia.com'],
            [
                'name' => 'Manager User',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'employee_id' => 'MGR001',
                'phone' => '081200000002',
                'department_id' => null,
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'employee@presensia.com'],
            [
                'name' => 'Employee User',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'employee_id' => 'EMP001',
                'phone' => '081200000003',
                'department_id' => null,
                'is_active' => true,
            ]
        );
    }
}
