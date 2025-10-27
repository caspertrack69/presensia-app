@extends('layouts.app')

@section('title', 'Scan QR Absensi')

@section('content')
<div class="min-h-screen bg-slate-900 py-10 px-4" x-data="qrScanner()">
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

@push('scripts')
<script type="module">
import { BrowserMultiFormatReader } from '@zxing/library';

document.addEventListener('alpine:init', () => {
    Alpine.data('qrScanner', () => ({
        codeReader: null,
        scanning: false,
        loading: false,
        loadingMessage: '',
        scanSuccess: false,
        scanError: false,
        successMessage: '',
        errorMessage: '',
        qrData: null,

        init() {
            this.codeReader = new BrowserMultiFormatReader();
            this.startCamera();
        },

        async startCamera() {
            this.scanning = true;
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                this.$refs.video.srcObject = stream;
                await this.readQrCode();
            } catch (error) {
                console.error('Camera error:', error);
                this.showError('Tidak dapat mengakses kamera. Pastikan izin kamera telah diberikan.');
            }
        },

        async readQrCode() {
            if (!this.codeReader) {
                return;
            }

            try {
                const result = await this.codeReader.decodeFromVideoDevice(null, this.$refs.video, (result) => {
                    if (!result) {
                        return;
                    }

                    this.codeReader.reset();
                    this.scanning = false;
                    this.verifyQrCode(result.getText());
                });
            } catch (error) {
                console.error('QR scan error:', error);
                this.showError('Gagal membaca QR Code. Silakan coba lagi.');
            }
        },

        async verifyQrCode(token) {
            this.loading = true;
            this.loadingMessage = 'Memverifikasi QR Code...';

            try {
                const response = await fetch('{{ route('employee.attendance.verify-qr') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ token }),
                });

                const result = await response.json();

                if (result.success) {
                    this.qrData = { token, ...result.data };
                    this.scanSuccess = true;
                    this.successMessage = `QR Code valid untuk lokasi ${result.data.location}`;
                } else {
                    this.showError(result.message);
                }
            } catch (error) {
                console.error('Verify QR error:', error);
                this.showError('Terjadi kesalahan saat memverifikasi QR Code.');
            } finally {
                this.loading = false;
            }
        },

        async processCheckIn() {
            await this.processAttendance('check-in', 'Check-in');
        },

        async processCheckOut() {
            await this.processAttendance('check-out', 'Check-out');
        },

        async processAttendance(action, label) {
            this.loading = true;
            this.loadingMessage = 'Mengambil foto selfie...';

            try {
                const selfie = await this.captureSelfie();
                this.loadingMessage = `${label} sedang diproses...`;

                const location = await this.getLocation();

                const response = await fetch(`{{ url('employee/attendance') }}/${action}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        token: this.qrData.token,
                        selfie,
                        latitude: location?.latitude,
                        longitude: location?.longitude,
                    }),
                });

                const result = await response.json();
                if (result.success) {
                    this.showSuccess(result.message);
                } else {
                    this.showError(result.message);
                }
            } catch (error) {
                console.error('Attendance error:', error);
                this.showError(error.message ?? 'Terjadi kesalahan saat memproses absensi.');
            } finally {
                this.loading = false;
            }
        },

        async captureSelfie() {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
            this.$refs.video.srcObject = stream;

            await new Promise((resolve) => setTimeout(resolve, 1000));

            const canvas = document.createElement('canvas');
            canvas.width = this.$refs.video.videoWidth;
            canvas.height = this.$refs.video.videoHeight;
            canvas.getContext('2d').drawImage(this.$refs.video, 0, 0);

            const rearStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
            this.$refs.video.srcObject = rearStream;

            return canvas.toDataURL('image/jpeg');
        },

        async getLocation() {
            if (!navigator.geolocation) {
                return null;
            }

            return new Promise((resolve) => {
                navigator.geolocation.getCurrentPosition(
                    (position) => resolve({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                    }),
                    () => resolve(null),
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            });
        },

        showSuccess(message) {
            this.scanSuccess = true;
            this.successMessage = message;
            this.stopCamera();
            setTimeout(() => (window.location.href = '{{ route('employee.dashboard') }}'), 2500);
        },

        showError(message) {
            this.scanError = true;
            this.errorMessage = message;
            this.stopCamera();
        },

        resetScanner() {
            this.scanError = false;
            this.scanSuccess = false;
            this.qrData = null;
            this.startCamera();
        },

        stopCamera() {
            if (this.codeReader) {
                this.codeReader.reset();
            }
            if (this.$refs.video?.srcObject) {
                this.$refs.video.srcObject.getTracks().forEach((track) => track.stop());
            }
            this.scanning = false;
        },

        destroy() {
            this.stopCamera();
        },
    }));
});
</script>
@endpush
@endsection
