<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\QrCode;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the administrative dashboard with key metrics.
     */
    public function index()
    {
        $today = Carbon::today();

        $employeeCount = User::where('role', 'employee')->count();
        $managerCount = User::where('role', 'manager')->count();
        $activeUsers = User::where('is_active', true)->count();

        $todayAttendance = Attendance::with('user')
            ->whereDate('date', $today)
            ->get();

        $attendanceSummary = [
            'present' => $todayAttendance->where('status', 'present')->count(),
            'late' => $todayAttendance->where('status', 'late')->count(),
            'leave' => $todayAttendance->where('status', 'leave')->count(),
            'absent' => $todayAttendance->where('status', 'absent')->count(),
        ];

        $recentAttendance = Attendance::with('user')
            ->orderByDesc('date')
            ->orderByDesc('check_in')
            ->limit(10)
            ->get();

        $pendingLeaveRequests = LeaveRequest::with('user')
            ->whereIn('status', ['pending', 'approved_manager'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $activeQrCodes = QrCode::where('is_active', true)
            ->where('expires_at', '>', now())
            ->count();

        return view('admin.dashboard', [
            'employeeCount' => $employeeCount,
            'managerCount' => $managerCount,
            'activeUsers' => $activeUsers,
            'attendanceSummary' => $attendanceSummary,
            'recentAttendance' => $recentAttendance,
            'pendingLeaveRequests' => $pendingLeaveRequests,
            'activeQrCodes' => $activeQrCodes,
        ]);
    }
}
