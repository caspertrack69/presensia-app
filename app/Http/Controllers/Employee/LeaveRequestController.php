<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the user's leave requests.
     */
    public function index()
    {
        $leaveRequests = Auth::user()
            ->leaveRequests()
            ->latest()
            ->paginate(10);

        return view('employee.leave-requests.index', compact('leaveRequests'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        return view('employee.leave-requests.create');
    }

    /**
     * Store a newly created leave request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => ['required', Rule::in(['sick', 'annual', 'unpaid', 'other'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'min:10'],
            'attachment' => ['nullable', 'file', 'max:4096', 'mimes:pdf,jpg,jpeg,png'],
        ]);

        $user = Auth::user();

        $daysCount = Carbon::parse($validated['start_date'])
            ->diffInDays(Carbon::parse($validated['end_date'])) + 1;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store(
                'leave-attachments/' . $user->id,
                'public'
            );
        }

        $leaveRequest = new LeaveRequest([
            'type' => $validated['type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'days_count' => $daysCount,
            'reason' => $validated['reason'],
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        $user->leaveRequests()->save($leaveRequest);

        return redirect()
            ->route('employee.leave-requests.index')
            ->with('status', 'Pengajuan izin berhasil dikirim dan menunggu persetujuan.');
    }

    /**
     * Display the specified leave request.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        abort_unless($leaveRequest->user_id === Auth::id(), 403);

        $leaveRequest->load(['managerApprover', 'adminApprover']);

        return view('employee.leave-requests.show', compact('leaveRequest'));
    }
}
