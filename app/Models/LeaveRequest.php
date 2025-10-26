<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'days_count',
        'reason',
        'attachment',
        'status',
        'approved_by_manager',
        'manager_approved_at',
        'manager_notes',
        'approved_by_admin',
        'admin_approved_at',
        'admin_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'manager_approved_at' => 'datetime',
        'admin_approved_at' => 'datetime',
    ];

    /**
     * Get the user who made the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the manager who approved the request
     */
    public function managerApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_manager');
    }

    /**
     * Get the admin who approved the request
     */
    public function adminApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_admin');
    }

    /**
     * Check if request is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if request is approved by manager
     */
    public function isApprovedByManager(): bool
    {
        return $this->status === 'approved_manager';
    }

    /**
     * Check if request is fully approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if request is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
