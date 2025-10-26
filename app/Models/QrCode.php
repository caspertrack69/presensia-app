<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'location_name',
        'latitude',
        'longitude',
        'expires_at',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user who created this QR code
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all attendances using this QR code
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Check if QR code is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if QR code is valid for use
     */
    public function isValid(): bool
    {
        return $this->is_active && !$this->isExpired();
    }

    /**
     * Generate a new unique token
     */
    public static function generateToken(): string
    {
        return Str::random(64);
    }

    /**
     * Create a new dynamic QR code
     */
    public static function createDynamic(string $locationName, int $createdBy, int $expiresInSeconds = 60, ?float $latitude = null, ?float $longitude = null): self
    {
        return self::create([
            'token' => self::generateToken(),
            'location_name' => $locationName,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'expires_at' => now()->addSeconds($expiresInSeconds),
            'is_active' => true,
            'created_by' => $createdBy,
        ]);
    }
}
