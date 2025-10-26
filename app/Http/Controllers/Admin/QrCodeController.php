<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QrCodeController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Show QR code display page (for kiosk/monitor)
     */
    public function display()
    {
        return view('admin.qrcode.display');
    }

    /**
     * Generate new QR code
     */
    public function generate(Request $request)
    {
        $request->validate([
            'location_name' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $qrCode = $this->qrCodeService->generateDynamicQrCode(
            $request->location_name,
            Auth::id(),
            $request->latitude,
            $request->longitude
        );

        // Generate QR code image
        $qrImage = $this->qrCodeService->generateQrCodeImage($qrCode->token, 'svg');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $qrCode->id,
                'token' => $qrCode->token,
                'location_name' => $qrCode->location_name,
                'expires_at' => $qrCode->expires_at->toISOString(),
                'qr_image' => base64_encode($qrImage),
                'expiry_seconds' => Setting::get('qr_code_expiry_seconds', 60),
            ]
        ]);
    }

    /**
     * Get current active QR code
     */
    public function current(Request $request)
    {
        $locationName = $request->input('location_name', 'Main Office');
        
        $qrCode = $this->qrCodeService->getActiveQrCode($locationName);

        if (!$qrCode) {
            return response()->json([
                'success' => false,
                'message' => 'No active QR code found'
            ], 404);
        }

        $qrImage = $this->qrCodeService->generateQrCodeImage($qrCode->token, 'svg');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $qrCode->id,
                'token' => $qrCode->token,
                'location_name' => $qrCode->location_name,
                'expires_at' => $qrCode->expires_at->toISOString(),
                'qr_image' => base64_encode($qrImage),
                'expiry_seconds' => Setting::get('qr_code_expiry_seconds', 60),
            ]
        ]);
    }

    /**
     * List all QR codes
     */
    public function index()
    {
        $qrCodes = \App\Models\QrCode::with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.qrcode.index', compact('qrCodes'));
    }
}
