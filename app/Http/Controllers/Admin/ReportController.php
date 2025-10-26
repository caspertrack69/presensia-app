<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Show attendance report filters and results.
     */
    public function attendance(Request $request)
    {
        $departments = Department::orderBy('name')->get();
        $departmentId = $request->input('department_id');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $attendances = Attendance::with(['user.department'])
            ->whereBetween('date', [$startDate, $endDate])
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->whereHas('user', function ($userQuery) use ($departmentId) {
                    $userQuery->where('department_id', $departmentId);
                });
            })
            ->orderByDesc('date')
            ->orderBy('user_id')
            ->paginate(25)
            ->appends($request->only(['department_id', 'start_date', 'end_date']));

        return view('admin.reports.attendance', [
            'departments' => $departments,
            'attendances' => $attendances,
            'departmentId' => $departmentId,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    /**
     * Export attendance report to Excel.
     */
    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'department_id' => ['nullable', 'exists:departments,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        ]);

        $records = Attendance::with(['user.department'])
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
            ->when($validated['department_id'] ?? null, function ($query, $departmentId) {
                $query->whereHas('user', function ($userQuery) use ($departmentId) {
                    $userQuery->where('department_id', $departmentId);
                });
            })
            ->orderBy('date')
            ->orderBy('user_id')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray([
            ['Tanggal', 'Nama', 'Departemen', 'Check In', 'Check Out', 'Status'],
        ]);

        $row = 2;
        foreach ($records as $attendance) {
            $sheet->fromArray([[
                $attendance->date->format('Y-m-d'),
                $attendance->user?->name,
                $attendance->user?->department?->name,
                optional($attendance->check_in)->format('H:i:s') ?? '-',
                optional($attendance->check_out)->format('H:i:s') ?? '-',
                ucfirst($attendance->status),
            ]], null, "A{$row}");
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'attendance-report-' . now()->format('Ymd-His') . '.xlsx';

        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', "attachment; filename=\"{$fileName}\"");

        return $response;
    }
}
