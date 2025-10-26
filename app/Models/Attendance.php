<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Setting;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'qr_code_id',
        'date',
        'check_in',
        'check_out',
        'check_in_selfie',
        'check_out_selfie',
        'check_in_latitude',
        'check_in_longitude',
        'check_out_latitude',
        'check_out_longitude',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'check_in_latitude' => 'decimal:8',
        'check_in_longitude' => 'decimal:8',
        'check_out_latitude' => 'decimal:8',
        'check_out_longitude' => 'decimal:8',
    ];

    /**
     * Get the user that owns the attendance
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the QR code used for this attendance
     */
    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }

    /**
     * Check if user is late
     */
    public function isLate(): bool
    {
        if (!$this->check_in) {
            return false;
        }

        $workStartTime = Setting::where('key', 'work_start_time')->first()?->value ?? '09:00:00';
        $lateThreshold = Setting::where('key', 'late_threshold_minutes')->first()?->value ?? 15;

        $checkInTime = \Carbon\Carbon::parse($this->check_in);
        $startTime = \Carbon\Carbon::parse($this->date->format('Y-m-d') . ' ' . $workStartTime);

        return $checkInTime->greaterThan($startTime->addMinutes($lateThreshold));
    }
}
