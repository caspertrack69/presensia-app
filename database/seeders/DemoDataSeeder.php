<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use App\Models\QrCode;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@presensia.com')->first();
        $manager = User::where('email', 'manager@presensia.com')->first();

        $departments = Department::query()->get()->keyBy('code');

        $employees = [
            [
                'name' => 'Ayla Hermawan',
                'email' => 'ayla.hermawan@presensia.com',
                'employee_id' => 'HR002',
                'phone' => '081210000012',
                'department_code' => 'HRD',
            ],
            [
                'name' => 'Bima Pratama',
                'email' => 'bima.pratama@presensia.com',
                'employee_id' => 'OPS002',
                'phone' => '081210000013',
                'department_code' => 'OPS',
            ],
            [
                'name' => 'Citra Laksmi',
                'email' => 'citra.laksmi@presensia.com',
                'employee_id' => 'IT003',
                'phone' => '081210000014',
                'department_code' => 'ITD',
            ],
            [
                'name' => 'Dimas Wirawan',
                'email' => 'dimas.wirawan@presensia.com',
                'employee_id' => 'SAL004',
                'phone' => '081210000015',
                'department_code' => 'SAL',
            ],
            [
                'name' => 'Eka Sugiarto',
                'email' => 'eka.sugiarto@presensia.com',
                'employee_id' => 'OPS005',
                'phone' => '081210000016',
                'department_code' => 'OPS',
            ],
        ];

        foreach ($employees as $employeeData) {
            $departmentId = $departments[$employeeData['department_code']]->id ?? null;

            User::updateOrCreate(
                ['email' => $employeeData['email']],
                [
                    'name' => $employeeData['name'],
                    'password' => Hash::make('password'),
                    'role' => 'employee',
                    'employee_id' => $employeeData['employee_id'],
                    'phone' => $employeeData['phone'],
                    'department_id' => $departmentId,
                    'is_active' => true,
                ]
            );
        }

        $seededEmployee = User::where('email', 'employee@presensia.com')->first();
        if ($seededEmployee && isset($departments['OPS'])) {
            $seededEmployee->update(['department_id' => $departments['OPS']->id, 'phone' => $seededEmployee->phone ?: '081210000010']);
        }

        if ($manager && isset($departments['OPS']) && !$manager->department_id) {
            $manager->update(['department_id' => $departments['OPS']->id]);
        }

        $qrCodes = [
            QrCode::updateOrCreate(
                ['token' => 'demo-hq-lobby'],
                [
                    'location_name' => 'HQ - Lobby Utama',
                    'latitude' => -6.200000,
                    'longitude' => 106.816666,
                    'expires_at' => now()->addHours(12),
                    'is_active' => true,
                    'created_by' => $admin?->id ?? $manager?->id,
                ]
            ),
            QrCode::updateOrCreate(
                ['token' => 'demo-warehouse-yard'],
                [
                    'location_name' => 'Gudang - Area Belakang',
                    'latitude' => -6.201500,
                    'longitude' => 106.820000,
                    'expires_at' => now()->addHours(12),
                    'is_active' => true,
                    'created_by' => $admin?->id ?? $manager?->id,
                ]
            ),
        ];

        $attendanceUsers = User::where('role', 'employee')->take(6)->get();
        $baseLat = -6.200500;
        $baseLng = 106.817200;

        foreach ($attendanceUsers as $index => $employee) {
            for ($day = 1; $day <= 5; $day++) {
                $date = Carbon::now()->subDays($day);

                $status = $day % 4 === 0 ? 'late' : 'present';
                $checkInTime = $date->copy()->setTime(8, 30 + ($index % 3) * 5, 0);
                if ($status === 'late') {
                    $checkInTime->addMinutes(25);
                }
                $checkOutTime = $date->copy()->setTime(17, 15 + ($index % 2) * 10, 0);

                Attendance::updateOrCreate(
                    [
                        'user_id' => $employee->id,
                        'date' => $date->toDateString(),
                    ],
                    [
                        'qr_code_id' => $qrCodes[$index % count($qrCodes)]->id,
                        'check_in' => $checkInTime,
                        'check_out' => $checkOutTime,
                        'check_in_latitude' => $baseLat + ($index * 0.0005),
                        'check_in_longitude' => $baseLng + ($index * 0.0003),
                        'check_out_latitude' => $baseLat + ($index * 0.0005),
                        'check_out_longitude' => $baseLng + ($index * 0.0003),
                        'status' => $status,
                        'notes' => $status === 'late'
                            ? 'Terlambat karena kemacetan lalu lintas.'
                            : 'Kehadiran tepat waktu.',
                    ]
                );
            }
        }

        $leaveSamples = [
            [
                'email' => 'ayla.hermawan@presensia.com',
                'type' => 'annual',
                'start_date' => Carbon::now()->addDays(3)->toDateString(),
                'end_date' => Carbon::now()->addDays(4)->toDateString(),
                'reason' => 'Cuti tahunan untuk menghadiri acara keluarga.',
                'status' => 'approved',
                'approved_by_manager' => $manager?->id,
                'approved_by_admin' => $admin?->id,
            ],
            [
                'email' => 'bima.pratama@presensia.com',
                'type' => 'sick',
                'start_date' => Carbon::now()->addDay()->toDateString(),
                'end_date' => Carbon::now()->addDays(2)->toDateString(),
                'reason' => 'Istirahat karena demam dan flu.',
                'status' => 'approved_manager',
                'approved_by_manager' => $manager?->id,
                'approved_by_admin' => null,
            ],
            [
                'email' => 'dimas.wirawan@presensia.com',
                'type' => 'other',
                'start_date' => Carbon::now()->addDays(7)->toDateString(),
                'end_date' => Carbon::now()->addDays(7)->toDateString(),
                'reason' => 'Mengurus administrasi kependudukan.',
                'status' => 'pending',
                'approved_by_manager' => null,
                'approved_by_admin' => null,
            ],
        ];

        foreach ($leaveSamples as $sample) {
            $user = User::where('email', $sample['email'])->first();
            if (! $user) {
                continue;
            }

            $startDate = Carbon::parse($sample['start_date']);
            $endDate = Carbon::parse($sample['end_date']);
            $daysCount = $startDate->diffInDays($endDate) + 1;

            LeaveRequest::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'start_date' => $startDate->toDateString(),
                    'type' => $sample['type'],
                ],
                [
                    'end_date' => $endDate->toDateString(),
                    'days_count' => $daysCount,
                    'reason' => $sample['reason'],
                    'status' => $sample['status'],
                    'approved_by_manager' => $sample['approved_by_manager'],
                    'manager_approved_at' => $sample['approved_by_manager'] ? now() : null,
                    'approved_by_admin' => $sample['approved_by_admin'],
                    'admin_approved_at' => $sample['approved_by_admin'] ? now() : null,
                    'manager_notes' => $sample['approved_by_manager'] ? 'Disetujui, pastikan handover pekerjaan.' : null,
                    'admin_notes' => $sample['approved_by_admin'] ? 'Disetujui untuk perhitungan payroll.' : null,
                ]
            );
        }
    }
}
