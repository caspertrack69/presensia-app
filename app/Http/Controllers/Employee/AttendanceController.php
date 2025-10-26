<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\AttendanceService;
use App\Services\QrCodeService;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected $attendanceService;
    protected $qrCodeService;

    public function __construct(AttendanceService $attendanceService, QrCodeService $qrCodeService)
    {
        $this->attendanceService = $attendanceService;
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Show QR scanner page
     */
    public function scanner()
    {
        return view('employee.attendance.scanner');
    }

    /**
     * Verify QR code
     */
    public function verifyQr(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $qrCode = $this->qrCodeService->verifyQrCode($request->token);

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau sudah kadaluarsa'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'QR Code valid',
            'data' => [
                'location' => $qrCode->location_name,
                'expires_at' => $qrCode->expires_at->toISOString(),
            ]
        ]);
    }

    /**
     * Check in
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'selfie' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        try {
            $qrCode = $this->qrCodeService->verifyQrCode($request->token);

            if (!$qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau sudah kadaluarsa'
                ], 400);
            }

            $requireGeolocation = Setting::get('require_geolocation', false);

            if ($requireGeolocation && (!$request->latitude || !$request->longitude)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi wajib diaktifkan untuk melakukan absensi.'
                ], 400);
            }

            // Validate geolocation if required
            if ($request->latitude && $request->longitude) {
                $isValidLocation = $this->attendanceService->validateGeolocation(
                    $request->latitude,
                    $request->longitude
                );

                if (!$isValidLocation) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lokasi Anda terlalu jauh dari kantor'
                    ], 400);
                }
            }

            $attendance = $this->attendanceService->checkIn(
                Auth::user(),
                $qrCode,
                $request->selfie,
                $request->latitude,
                $request->longitude
            );

            return response()->json([
                'success' => true,
                'message' => 'Check-in berhasil',
                'data' => [
                    'status' => $attendance->status,
                    'check_in' => $attendance->check_in->format('H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Check out
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'selfie' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        try {
            $qrCode = $this->qrCodeService->verifyQrCode($request->token);

            if (!$qrCode) {
                return response()->json([
                    'success' => false,
                    'message' => 'QR Code tidak valid atau sudah kadaluarsa'
                ], 400);
            }

            $requireGeolocation = Setting::get('require_geolocation', false);

            if ($requireGeolocation && (!$request->latitude || !$request->longitude)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi wajib diaktifkan untuk melakukan absensi.'
                ], 400);
            }

            if ($request->latitude && $request->longitude) {
                $isValidLocation = $this->attendanceService->validateGeolocation(
                    $request->latitude,
                    $request->longitude
                );

                if (!$isValidLocation) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Lokasi Anda terlalu jauh dari kantor'
                    ], 400);
                }
            }

            $attendance = $this->attendanceService->checkOut(
                Auth::user(),
                $qrCode,
                $request->selfie,
                $request->latitude,
                $request->longitude
            );

            return response()->json([
                'success' => true,
                'message' => 'Check-out berhasil',
                'data' => [
                    'check_out' => $attendance->check_out->format('H:i:s'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show attendance history
     */
    public function history()
    {
        $attendances = Auth::user()->attendances()
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('employee.attendance.history', compact('attendances'));
    }
}
