@extends('layouts.app')

@section('title', 'QR Code Display')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center p-8" x-data="qrDisplay()">
    <div class="max-w-2xl w-full space-y-6">
        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-12">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Presensia</h1>
                <p class="text-lg text-gray-600">Scan untuk Absensi</p>
            </div>

            <!-- Loading State -->
            <div class="mb-8 bg-gray-50 p-8 rounded-xl flex flex-col items-center justify-center text-center" x-show="loading" x-cloak>
                <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent mb-4"></div>
                <p class="font-semibold text-gray-600">Generating QR Code...</p>
            </div>

            <!-- Active QR Display -->
            <div class="space-y-6" x-show="!loading" x-cloak>
                <p class="font-semibold text-green-600 text-center">Aktif</p>
                <img :src="'data:image/svg+xml;base64,' + qrImage" alt="QR Code" class="mx-auto w-96 h-96" />

                <!-- Location Info -->
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-1">Lokasi</p>
                    <p class="text-xl font-semibold text-gray-900" x-text="locationName"></p>
                </div>

                <!-- Countdown Timer -->
                <div class="text-center">
                    <p class="text-sm text-gray-500 mb-2">QR Code akan diperbarui dalam</p>
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100">
                        <span class="text-3xl font-bold text-blue-600" x-text="countdown"></span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">detik</p>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="border-t border-gray-200 pt-6 mt-8">
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Waktu</p>
                        <p class="font-semibold text-gray-900" x-text="currentTime"></p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-900" x-text="currentDate"></p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Status</p>
                        <p class="font-semibold text-green-600" x-show="!loading" x-cloak>Aktif</p>
                        <p class="font-semibold text-gray-400" x-show="loading" x-cloak>Memuat...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="bg-white bg-opacity-90 rounded-lg p-6">
            <h3 class="font-semibold text-gray-900 mb-3">Cara Menggunakan:</h3>
            <ol class="text-sm text-gray-700 space-y-2">
                <li>1. Buka aplikasi Presensia di smartphone Anda</li>
                <li>2. Login dengan akun Anda</li>
                <li>3. Klik "Scan QR" di dashboard</li>
                <li>4. Arahkan kamera ke QR Code di layar ini</li>
                <li>5. Ikuti instruksi untuk mengambil foto selfie</li>
            </ol>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qrDisplay', () => ({
        qrImage: '',
        locationName: '{{ request('location', 'Main Office') }}',
        countdown: 0,
        expirySeconds: 60,
        currentTime: '',
        currentDate: '',
        loading: true,
        countdownInterval: null,
        clockInterval: null,
        
        init() {
            this.generateQrCode();
            this.updateClock();
            this.clockInterval = setInterval(() => this.updateClock(), 1000);
        },
        
        async generateQrCode() {
            this.loading = true;
            
            try {
                const response = await fetch('{{ route("admin.qrcode.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        location_name: this.locationName,
                        latitude: null,
                        longitude: null
                    })
                });
                
                if (!response.ok) {
                    throw new Error(`Request failed with status ${response.status}`);
                }

                const result = await response.json();
                
                if (result.success) {
                    this.qrImage = result.data.qr_image ?? '';
                    this.expirySeconds = result.data.expiry_seconds ?? 60;
                    if (result.data.location_name) {
                        this.locationName = result.data.location_name;
                    }
                    this.startCountdown();
                } else {
                    throw new Error(result.message ?? 'Failed to generate QR code');
                }
            } catch (error) {
                console.error('Generate QR error:', error);
                setTimeout(() => this.generateQrCode(), 5000);
            } finally {
                this.loading = false;
            }
        },
        
        startCountdown() {
            this.countdown = this.expirySeconds;
            
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
            }
            
            this.countdownInterval = setInterval(() => {
                this.countdown = Math.max(this.countdown - 1, 0);
                
                if (this.countdown <= 0) {
                    clearInterval(this.countdownInterval);
                    this.generateQrCode();
                }
            }, 1000);
        },
        
        updateClock() {
            const now = new Date();
            this.currentTime = now.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit'
            });
            this.currentDate = now.toLocaleDateString('id-ID', { 
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        },

        destroy() {
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
            }
            if (this.clockInterval) {
                clearInterval(this.clockInterval);
            }
        }
    }));
});
</script>
@endpush
@endsection
