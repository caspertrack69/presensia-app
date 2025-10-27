@extends('layouts.app')

@section('title', 'Scan QR Absensi')

@section('content')
<div
    class="min-h-screen bg-slate-900 py-10 px-4"
    x-data="qrScanner(@js([
        'verifyUrl' => route('employee.attendance.verify-qr'),
        'attendanceUrl' => url('employee/attendance'),
        'dashboardUrl' => route('employee.dashboard'),
    ]))"
>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex flex-col items-center gap-4 text-center text-white">
            <h1 class="text-3xl font-semibold">Scan QR untuk Absensi</h1>
            <p class="max-w-2xl text-sm text-slate-200">
                Izinkan akses kamera dan lokasi untuk memulai proses absensi. Sistem akan memverifikasi QR Code, selfie, dan lokasi Anda secara otomatis.
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-soft dark:border-slate-800 dark:bg-slate-900/80">
            <div class="overflow-hidden rounded-t-2xl bg-slate-950">
                <div class="relative aspect-square">
                    <video
                        x-ref="video"
                        autoplay
                        playsinline
                        class="h-full w-full object-cover"
                        x-show="!scanSuccess && !scanError"
                    ></video>

                    <div class="absolute inset-0 flex items-center justify-center" x-show="scanning">
                        <div class="h-64 w-64 rounded-2xl border-4 border-dashed border-white"></div>
                    </div>

                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center bg-emerald-600/95 p-10 text-center text-white"
                        x-show="scanSuccess"
                        x-transition
                    >
                        <svg class="mb-4 h-20 w-20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <h2 class="text-2xl font-semibold">Berhasil</h2>
                        <p class="mt-3 text-sm" x-text="successMessage"></p>
                    </div>

                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center bg-rose-600/95 p-10 text-center text-white"
                        x-show="scanError"
                        x-transition
                    >
                        <svg class="mb-4 h-20 w-20" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <h2 class="text-2xl font-semibold">Gagal</h2>
                        <p class="mt-3 text-sm" x-text="errorMessage"></p>
                        <x-ui.button class="mt-5" @click="resetScanner()" variant="outline">
                            Coba Lagi
                        </x-ui.button>
                    </div>

                    <div
                        class="absolute inset-0 flex items-center justify-center bg-slate-950/80 text-white"
                        x-show="loading"
                    >
                        <div class="text-center">
                            <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-4 border-white border-t-transparent"></div>
                            <p class="text-sm" x-text="loadingMessage"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-4 p-6">
                <div class="flex flex-col gap-3 md:flex-row">
                    <x-ui.button class="flex-1" @click="processCheckIn()" x-bind:disabled="!qrData || loading">
                        Check-in
                    </x-ui.button>
                    <x-ui.button class="flex-1" variant="secondary" @click="processCheckOut()" x-bind:disabled="!qrData || loading">
                        Check-out
                    </x-ui.button>
                </div>

                <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-300">
                    <p class="font-medium text-slate-700 dark:text-slate-200">Informasi Penting:</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5 text-slate-600 dark:text-slate-300">
                        <li>Pastikan kamera perangkat aktif dan diarahkan ke QR Code terbaru.</li>
                        <li>Setelah QR tervalidasi, sistem akan mengambil foto selfie otomatis.</li>
                        <li>Aktifkan layanan lokasi untuk memverifikasi posisi Anda.</li>
                        <li>Gunakan tombol Check-in atau Check-out setelah proses selesai.</li>
                    </ul>
                </div>

                <x-ui.button as="a" href="{{ route('employee.dashboard') }}" variant="ghost" class="w-full">
                    Kembali ke Dashboard
                </x-ui.button>
            </div>
        </div>
    </div>
</div>

@endsection
