<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\QrCode;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AttendanceService
{
    /**
     * Check in attendance
     */
    public function checkIn(
        User $user,
        QrCode $qrCode,
        string $selfieBase64,
        ?float $latitude = null,
        ?float $longitude = null
    ): Attendance {
        $today = Carbon::today();

        // Check if already checked in today
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            throw new \Exception('Already checked in today');
        }

        // Save selfie
        $selfiePath = $this->saveSelfie($selfieBase64, $user->id, 'check-in');

        // Determine status (present or late)
        $workStartTime = Setting::get('work_start_time', '09:00:00');
        $lateThreshold = Setting::get('late_threshold_minutes', 15);
        $checkInTime = now();
        $startTime = Carbon::parse($today->format('Y-m-d') . ' ' . $workStartTime);
        
        $status = $checkInTime->greaterThan($startTime->addMinutes($lateThreshold)) ? 'late' : 'present';

        // Create or update attendance
        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $today,
            ],
            [
                'qr_code_id' => $qrCode->id,
                'check_in' => $checkInTime,
                'check_in_selfie' => $selfiePath,
                'check_in_latitude' => $latitude,
                'check_in_longitude' => $longitude,
                'status' => $status,
            ]
        );

        return $attendance;
    }

    /**
     * Check out attendance
     */
    public function checkOut(
        User $user,
        QrCode $qrCode,
        string $selfieBase64,
        ?float $latitude = null,
        ?float $longitude = null
    ): Attendance {
        $today = Carbon::today();

        // Get today's attendance
        $attendance = Attendance::where('user_id', $user->id)
            ->where('date', $today)
            ->first();

        if (!$attendance) {
            throw new \Exception('No check-in record found for today');
        }

        if ($attendance->check_out) {
            throw new \Exception('Already checked out today');
        }

        // Save selfie
        $selfiePath = $this->saveSelfie($selfieBase64, $user->id, 'check-out');

        // Update attendance
        $attendance->update([
            'check_out' => now(),
            'check_out_selfie' => $selfiePath,
            'check_out_latitude' => $latitude,
            'check_out_longitude' => $longitude,
        ]);

        return $attendance;
    }

    /**
     * Save selfie image
     */
    protected function saveSelfie(string $base64Image, int $userId, string $type): string
    {
        // Remove data:image/png;base64, prefix if exists
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Image);
        $image = base64_decode($image);

        $filename = 'selfies/' . $userId . '/' . $type . '-' . now()->format('Y-m-d-His') . '.jpg';
        Storage::disk('public')->put($filename, $image);

        return $filename;
    }

    /**
     * Validate geolocation
     */
    public function validateGeolocation(?float $userLat, ?float $userLng): bool
    {
        if (!$userLat || !$userLng) {
            return false;
        }

        $officeLat = (float) Setting::get('office_latitude', -6.200000);
        $officeLng = (float) Setting::get('office_longitude', 106.816666);
        $maxDistance = (int) Setting::get('max_distance_meters', 100);

        $distance = $this->calculateDistance($userLat, $userLng, $officeLat, $officeLng);

        return $distance <= $maxDistance;
    }

    /**
     * Calculate distance between two coordinates in meters (Haversine formula)
     */
    protected function calculateDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371000; // meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get attendance summary for user
     */
    public function getAttendanceSummary(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        return [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', 'present')->count(),
            'late' => $attendances->where('status', 'late')->count(),
            'absent' => $attendances->where('status', 'absent')->count(),
            'leave' => $attendances->where('status', 'leave')->count(),
            'sick' => $attendances->where('status', 'sick')->count(),
        ];
    }

    /**
     * Mark absent for users who didn't check in
     */
    public function markAbsentUsers(): int
    {
        $today = Carbon::today();
        $workEndTime = Setting::get('work_end_time', '17:00:00');
        $endTime = Carbon::parse($today->format('Y-m-d') . ' ' . $workEndTime);

        // Only mark absent after work hours
        if (now()->lessThan($endTime)) {
            return 0;
        }

        // Get all active employees
        $activeUsers = User::where('is_active', true)
            ->where('role', 'employee')
            ->get();

        $absentCount = 0;

        foreach ($activeUsers as $user) {
            $attendance = Attendance::where('user_id', $user->id)
                ->where('date', $today)
                ->first();

            // If no attendance record or no check-in, mark as absent
            if (!$attendance || !$attendance->check_in) {
                Attendance::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'date' => $today,
                    ],
                    [
                        'status' => 'absent',
                    ]
                );
                $absentCount++;
            }
        }

        return $absentCount;
    }
}
