<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display the settings form.
     */
    public function index()
    {
        $keys = [
            'work_start_time',
            'work_end_time',
            'late_threshold_minutes',
            'qr_code_expiry_seconds',
            'max_distance_meters',
            'office_latitude',
            'office_longitude',
            'require_selfie',
            'require_geolocation',
        ];

        $settings = Setting::whereIn('key', $keys)
            ->get()
            ->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'work_start_time' => ['required', 'date_format:H:i'],
            'work_end_time' => ['required', 'date_format:H:i', 'after:work_start_time'],
            'late_threshold_minutes' => ['required', 'integer', 'min:0', 'max:180'],
            'qr_code_expiry_seconds' => ['required', 'integer', 'min:10', 'max:300'],
            'max_distance_meters' => ['required', 'integer', 'min:10', 'max:1000'],
            'office_latitude' => ['required', 'numeric'],
            'office_longitude' => ['required', 'numeric'],
            'require_selfie' => ['sometimes', 'boolean'],
            'require_geolocation' => ['sometimes', 'boolean'],
        ]);

        Setting::set('work_start_time', $data['work_start_time'], 'time');
        Setting::set('work_end_time', $data['work_end_time'], 'time');
        Setting::set('late_threshold_minutes', (string) $data['late_threshold_minutes'], 'integer');
        Setting::set('qr_code_expiry_seconds', (string) $data['qr_code_expiry_seconds'], 'integer');
        Setting::set('max_distance_meters', (string) $data['max_distance_meters'], 'integer');
        Setting::set('office_latitude', (string) $data['office_latitude'], 'string');
        Setting::set('office_longitude', (string) $data['office_longitude'], 'string');
        Setting::set('require_selfie', $request->boolean('require_selfie'), 'boolean');
        Setting::set('require_geolocation', $request->boolean('require_geolocation'), 'boolean');

        return redirect()
            ->route('admin.settings.index')
            ->with('status', 'Pengaturan berhasil diperbarui.');
    }
}
