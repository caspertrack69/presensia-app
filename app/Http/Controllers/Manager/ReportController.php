<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Show attendance report for manager's team.
     */
    public function attendance(Request $request)
    {
        $user = Auth::user();
        $teamMemberIds = $this->teamMemberIds($user);

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $attendances = Attendance::with('user')
            ->whereIn('user_id', $teamMemberIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->orderByDesc('date')
            ->paginate(25)
            ->appends($request->only(['start_date', 'end_date']));

        return view('manager.reports.attendance', [
            'attendances' => $attendances,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Export attendance report to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $user = Auth::user();
        $teamMemberIds = $this->teamMemberIds($user);

        $validated = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $records = Attendance::with('user')
            ->whereIn('user_id', $teamMemberIds)
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
            ->orderBy('date')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="team-attendance-report.csv"',
        ];

        $callback = function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Nama', 'Check In', 'Check Out', 'Status']);

            foreach ($records as $attendance) {
                fputcsv($handle, [
                    $attendance->date->format('Y-m-d'),
                    $attendance->user?->name,
                    optional($attendance->check_in)->format('H:i:s') ?? '-',
                    optional($attendance->check_out)->format('H:i:s') ?? '-',
                    ucfirst($attendance->status),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get IDs of users managed by current manager/admin.
     *
     * @return array<int, int>
     */
    protected function teamMemberIds($user): array
    {
        if ($user->isAdmin()) {
            return Department::query()
                ->with('users:id,department_id')
                ->get()
                ->flatMap(fn ($department) => $department->users->pluck('id'))
                ->unique()
                ->values()
                ->toArray();
        }

        return $user->managedDepartments()
            ->with(['users' => function ($query) {
                $query->where('is_active', true);
            }])
            ->get()
            ->flatMap(fn ($department) => $department->users->pluck('id'))
            ->unique()
            ->values()
            ->toArray();
    }
}
