<?php

namespace App\Services;

use App\Models\QrCode;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeGenerator;

class QrCodeService
{
    /**
     * Generate a new dynamic QR code
     */
    public function generateDynamicQrCode(string $locationName, int $createdBy, ?float $latitude = null, ?float $longitude = null): QrCode
    {
        // Deactivate previous QR codes for this location
        QrCode::where('location_name', $locationName)
            ->where('is_active', true)
            ->update(['is_active' => false]);

        // Get QR code expiry setting
        $expirySeconds = Setting::get('qr_code_expiry_seconds', 60);

        // Create new QR code
        $qrCode = QrCode::createDynamic(
            $locationName,
            $createdBy,
            $expirySeconds,
            $latitude,
            $longitude
        );

        return $qrCode;
    }

    /**
     * Generate QR code image
     */
    public function generateQrCodeImage(string $token, string $format = 'png'): string
    {
        $url = route('attendance.scan', ['token' => $token]);
        
        return QrCodeGenerator::format($format)
            ->size(300)
            ->margin(2)
            ->generate($url);
    }

    /**
     * Save QR code image to storage
     */
    public function saveQrCodeImage(string $token): string
    {
        $image = $this->generateQrCodeImage($token);
        $filename = 'qr-codes/' . $token . '.png';
        
        Storage::disk('public')->put($filename, $image);
        
        return $filename;
    }

    /**
     * Verify QR code token
     */
    public function verifyQrCode(string $token): ?QrCode
    {
        $qrCode = QrCode::where('token', $token)->first();

        if (!$qrCode) {
            return null;
        }

        if (!$qrCode->isValid()) {
            return null;
        }

        return $qrCode;
    }

    /**
     * Clean up expired QR codes
     */
    public function cleanupExpiredQrCodes(): int
    {
        return QrCode::where('expires_at', '<', now())
            ->where('is_active', true)
            ->update(['is_active' => false]);
    }

    /**
     * Get current active QR code for location
     */
    public function getActiveQrCode(string $locationName): ?QrCode
    {
        return QrCode::where('location_name', $locationName)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();
    }
}
