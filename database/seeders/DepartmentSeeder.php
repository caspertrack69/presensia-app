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

        $definitions = [
            [
                'code' => 'HRD',
                'name' => 'Human Resources',
                'description' => 'Mengelola strategi personel, rekrutmen, dan kebijakan kepegawaian.',
                'manager_id' => $manager?->id,
            ],
            [
                'code' => 'OPS',
                'name' => 'Operations',
                'description' => 'Mengawasi keberlangsungan operasional dan kepatuhan absensi harian.',
                'manager_id' => $manager?->id,
            ],
            [
                'code' => 'ITD',
                'name' => 'Information Technology',
                'description' => 'Mendukung infrastruktur teknologi dan keamanan sistem Presensia.',
                'manager_id' => null,
            ],
            [
                'code' => 'SAL',
                'name' => 'Sales & Marketing',
                'description' => 'Mengelola pipeline penjualan dan kampanye brand perusahaan.',
                'manager_id' => null,
            ],
        ];

        $departments = collect();

        foreach ($definitions as $definition) {
            $departments[$definition['code']] = Department::updateOrCreate(
                ['code' => $definition['code']],
                [
                    'name' => $definition['name'],
                    'description' => $definition['description'],
                    'manager_id' => $definition['manager_id'],
                    'is_active' => true,
                ]
            );
        }

        if ($manager && isset($departments['OPS']) && $manager->department_id !== $departments['OPS']->id) {
            $manager->update(['department_id' => $departments['OPS']->id]);
        }

        $employee = User::where('email', 'employee@presensia.com')->first();
        if ($employee && isset($departments['OPS']) && $employee->department_id !== $departments['OPS']->id) {
            $employee->update(['department_id' => $departments['OPS']->id]);
        }
    }
}
