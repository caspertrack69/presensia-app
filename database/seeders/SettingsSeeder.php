<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'work_start_time',
                'value' => '09:00:00',
                'type' => 'time',
                'description' => 'Default work start time'
            ],
            [
                'key' => 'work_end_time',
                'value' => '17:00:00',
                'type' => 'time',
                'description' => 'Default work end time'
            ],
            [
                'key' => 'late_threshold_minutes',
                'value' => '15',
                'type' => 'integer',
                'description' => 'Minutes after work start time before marked as late'
            ],
            [
                'key' => 'qr_code_expiry_seconds',
                'value' => '60',
                'type' => 'integer',
                'description' => 'QR code expiry time in seconds'
            ],
            [
                'key' => 'max_distance_meters',
                'value' => '100',
                'type' => 'integer',
                'description' => 'Maximum distance from office location in meters'
            ],
            [
                'key' => 'office_latitude',
                'value' => '-6.200000',
                'type' => 'string',
                'description' => 'Office location latitude'
            ],
            [
                'key' => 'office_longitude',
                'value' => '106.816666',
                'type' => 'string',
                'description' => 'Office location longitude'
            ],
            [
                'key' => 'require_selfie',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Require selfie for attendance'
            ],
            [
                'key' => 'require_geolocation',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Require geolocation for attendance'
            ],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
