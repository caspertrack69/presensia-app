<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\LeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveApprovalController extends Controller
{
    /**
     * Display a listing of pending leave requests for the manager's team.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $teamMemberIds = $this->teamMemberIds($user);

        $statusFilter = $request->input('status', 'pending');

        $leaveRequests = LeaveRequest::with('user')
            ->whereIn('user_id', $teamMemberIds)
            ->when($statusFilter === 'pending', function ($query) {
                $query->where('status', 'pending');
            }, function ($query) use ($statusFilter) {
                $query->where('status', $statusFilter);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(['status' => $statusFilter]);

        return view('manager.leave-requests.index', compact('leaveRequests', 'statusFilter'));
    }

    /**
     * Approve the specified leave request at manager level.
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        $this->assertCanManage($leaveRequest);

        if ($leaveRequest->status !== 'pending') {
            return back()->with('error', 'Pengajuan tidak dalam status menunggu persetujuan manajer.');
        }

        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $leaveRequest->update([
            'status' => 'approved_manager',
            'approved_by_manager' => Auth::id(),
            'manager_approved_at' => now(),
            'manager_notes' => $data['notes'] ?? null,
        ]);

        return back()->with('status', 'Pengajuan izin berhasil disetujui dan diteruskan ke admin.');
    }

    /**
     * Reject the specified leave request at manager level.
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->assertCanManage($leaveRequest);

        if (!in_array($leaveRequest->status, ['pending', 'approved_manager'], true)) {
            return back()->with('error', 'Pengajuan tidak dapat ditolak pada status saat ini.');
        }

        $data = $request->validate([
            'notes' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by_manager' => Auth::id(),
            'manager_approved_at' => now(),
            'manager_notes' => $data['notes'],
        ]);

        return back()->with('status', 'Pengajuan izin telah ditolak.');
    }

    /**
     * Ensure current user can manage the leave request.
     */
    protected function assertCanManage(LeaveRequest $leaveRequest): void
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return;
        }

        $teamMemberIds = $this->teamMemberIds($user);

        abort_unless(in_array($leaveRequest->user_id, $teamMemberIds, true), 403);
    }

    /**
     * Get IDs of team members managed by the given user.
     *
     * @return array<int, int>
     */
    protected function teamMemberIds($user): array
    {
        if ($user->isAdmin()) {
            return LeaveRequest::query()
                ->select('user_id')
                ->distinct()
                ->pluck('user_id')
                ->toArray();
        }

        return Department::where('manager_id', $user->id)
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
