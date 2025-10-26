<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where('role', 'manager')->first();

        $hrDepartment = Department::firstOrCreate(
            ['code' => 'HRD'],
            [
                'name' => 'Human Resources',
                'description' => 'Departemen yang mengelola kepegawaian dan absensi.',
                'manager_id' => $manager?->id,
                'is_active' => true,
            ]
        );

        Department::firstOrCreate(
            ['code' => 'FIN'],
            [
                'name' => 'Finance',
                'description' => 'Departemen keuangan dan akuntansi.',
                'manager_id' => null,
                'is_active' => true,
            ]
        );

        if ($manager && !$manager->department_id && $hrDepartment) {
            $manager->update(['department_id' => $hrDepartment->id]);
        }

        $employee = User::where('role', 'employee')->first();
        if ($employee && !$employee->department_id && $hrDepartment) {
            $employee->update(['department_id' => $hrDepartment->id]);
        }
    }
}
