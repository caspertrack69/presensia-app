<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of leave requests awaiting admin action.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'pending');

        $leaveRequests = LeaveRequest::with(['user', 'managerApprover'])
            ->when($status === 'pending', function ($query) {
                $query->whereIn('status', ['pending', 'approved_manager']);
            }, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->orderByDesc('created_at')
            ->paginate(15)
            ->appends(['status' => $status]);

        return view('admin.leave-requests.index', compact('leaveRequests', 'status'));
    }

    /**
     * Approve the specified leave request.
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        if (!in_array($leaveRequest->status, ['pending', 'approved_manager'], true)) {
            return back()->with('error', 'Pengajuan tidak dapat disetujui pada status saat ini.');
        }

        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by_admin' => Auth::id(),
            'admin_approved_at' => now(),
            'admin_notes' => $data['notes'] ?? null,
        ]);

        return back()->with('status', 'Pengajuan izin berhasil disetujui.');
    }

    /**
     * Reject the specified leave request.
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        if (!in_array($leaveRequest->status, ['pending', 'approved_manager'], true)) {
            return back()->with('error', 'Pengajuan tidak dapat ditolak pada status saat ini.');
        }

        $data = $request->validate([
            'notes' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by_admin' => Auth::id(),
            'admin_approved_at' => now(),
            'admin_notes' => $data['notes'],
        ]);

        return back()->with('status', 'Pengajuan izin berhasil ditolak.');
    }
}
