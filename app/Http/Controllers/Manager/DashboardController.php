<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the manager dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $managedDepartments = $user->isAdmin()
            ? Department::orderBy('name')->get()
            : $user->managedDepartments()->orderBy('name')->get();

        $selectedDepartmentId = $request->input('department_id');

        if (!$selectedDepartmentId && $managedDepartments->isNotEmpty()) {
            $selectedDepartmentId = $managedDepartments->first()->id;
        }

        $selectedDepartment = null;
        $teamMembers = collect();

        if ($selectedDepartmentId) {
            $selectedDepartment = Department::with(['users' => function ($query) {
                $query->where('is_active', true)->orderBy('name');
            }])->findOrFail($selectedDepartmentId);

            if (!$user->isAdmin() && $selectedDepartment->manager_id !== $user->id) {
                abort(403);
            }

            $teamMembers = $selectedDepartment->users;
        }

        $today = Carbon::today();

        $attendanceStats = [
            'present' => 0,
            'late' => 0,
            'absent' => 0,
            'leave' => 0,
            'sick' => 0,
        ];

        $todayAttendance = collect();
        if ($teamMembers->isNotEmpty()) {
            $todayAttendance = Attendance::whereIn('user_id', $teamMembers->pluck('id'))
                ->whereDate('date', $today)
                ->with('user')
                ->get();

            foreach ($attendanceStats as $status => $count) {
                $attendanceStats[$status] = $todayAttendance->where('status', $status)->count();
            }
        }

        $recentLeaveRequests = LeaveRequest::with('user')
            ->whereIn('user_id', $teamMembers->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('manager.dashboard', [
            'departments' => $managedDepartments,
            'selectedDepartment' => $selectedDepartment,
            'attendanceStats' => $attendanceStats,
            'todayAttendance' => $todayAttendance,
            'recentLeaveRequests' => $recentLeaveRequests,
        ]);
    }
}
