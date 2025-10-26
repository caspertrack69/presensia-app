@extends('layouts.app')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Pengaturan Sistem</h1>
                <p class="mt-1 text-sm text-slate-500">Atur jam kerja, keamanan absensi, dan parameter utama lainnya.</p>
            </div>
            <x-ui.button as="a" href="{{ route('admin.dashboard') }}" variant="outline">
                Kembali ke Dashboard
            </x-ui.button>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <x-ui.card>
            <x-ui.card-content>
                <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="work_start_time" class="text-sm font-medium text-slate-700">Jam Mulai Kerja</label>
                            <input id="work_start_time" type="time" name="work_start_time" value="{{ $settings['work_start_time']->value ?? '09:00' }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('work_start_time')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="work_end_time" class="text-sm font-medium text-slate-700">Jam Selesai Kerja</label>
                            <input id="work_end_time" type="time" name="work_end_time" value="{{ $settings['work_end_time']->value ?? '17:00' }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('work_end_time')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="late_threshold_minutes" class="text-sm font-medium text-slate-700">Toleransi Keterlambatan (menit)</label>
                            <input id="late_threshold_minutes" type="number" min="0" name="late_threshold_minutes" value="{{ $settings['late_threshold_minutes']->value ?? 15 }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('late_threshold_minutes')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="qr_code_expiry_seconds" class="text-sm font-medium text-slate-700">Durasi QR Dinamis (detik)</label>
                            <input id="qr_code_expiry_seconds" type="number" min="10" max="300" name="qr_code_expiry_seconds" value="{{ $settings['qr_code_expiry_seconds']->value ?? 60 }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('qr_code_expiry_seconds')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="max_distance_meters" class="text-sm font-medium text-slate-700">Jarak Maksimal Kantor (meter)</label>
                            <input id="max_distance_meters" type="number" min="10" max="1000" name="max_distance_meters" value="{{ $settings['max_distance_meters']->value ?? 100 }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('max_distance_meters')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="office_latitude" class="text-sm font-medium text-slate-700">Latitude Kantor</label>
                            <input id="office_latitude" type="number" step="0.000001" name="office_latitude" value="{{ $settings['office_latitude']->value ?? 0 }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('office_latitude')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="office_longitude" class="text-sm font-medium text-slate-700">Longitude Kantor</label>
                            <input id="office_longitude" type="number" step="0.000001" name="office_longitude" value="{{ $settings['office_longitude']->value ?? 0 }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('office_longitude')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-sm font-medium text-slate-700">Fitur Keamanan</p>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-4 space-y-3">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="require_selfie" value="1" class="h-4 w-4 border-slate-300 text-primary-600 focus:ring-primary-500" @checked(($settings['require_selfie']->value ?? true))>
                                <span class="text-sm text-slate-700">Wajibkan selfie saat absensi</span>
                            </label>
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="require_geolocation" value="1" class="h-4 w-4 border-slate-300 text-primary-600 focus:ring-primary-500" @checked(($settings['require_geolocation']->value ?? true))>
                                <span class="text-sm text-slate-700">Validasi geolokasi saat absensi</span>
                            </label>
                        </div>
                        <p class="text-xs text-slate-500">Pastikan koordinat kantor dan jarak maksimal disesuaikan agar validasi geolokasi akurat.</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <x-ui.button as="a" href="{{ route('admin.dashboard') }}" variant="ghost">
                            Batal
                        </x-ui.button>
                        <x-ui.button type="submit">
                            Simpan Pengaturan
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection
