@extends('layouts.app')

@section('title', 'QR Code Display')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center p-8" x-data="qrDisplay()">
    <div class="max-w-2xl w-full">
        <!-- Main Card -->
        <div class="bg-white rounded-2xl shadow-2xl p-12 text-center">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Presensia</h1>
                <p class="text-lg text-gray-600">Scan untuk Absensi</p>
            </div>

            <!-- QR Code Display -->
            <div class="mb-8 bg-gray-50 p-8 rounded-xl">
                        <p class="font-semibold text-gray-400" x-show="loading">Memuat...</p>
                    <div class="text-center">
                        <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent mb-4"></div>
                        <p class="text-gray-600">Generating QR Code...</p>
                    </div>
                </div>

                        <p class="font-semibold text-green-600" x-show="!loading">Aktif</p>
                    <img :src="'data:image/svg+xml;base64,' + qrImage" alt="QR Code" class="w-96 h-96" />
                    
                    <!-- Location Info -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500 mb-1">Lokasi</p>
                        <p class="text-xl font-semibold text-gray-900" x-text="locationName"></p>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="mt-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 mb-2">QR Code akan diperbarui dalam</p>
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100">
                                <span class="text-3xl font-bold text-blue-600" x-text="countdown"></span>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">detik</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Info -->
            <div class="border-t border-gray-200 pt-6">
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
                        <p class="font-semibold text-green-600" x-show="!loading">Aktif</p>
                        <p class="font-semibold text-gray-400" x-show="loading">Memuat...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Instructions Card -->
        <div class="mt-6 bg-white bg-opacity-90 rounded-lg p-6">
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
        qrImage: null,
        locationName: '{{ request('location', 'Main Office') }}',
        countdown: 0,
        expirySeconds: 60,
        currentTime: '',
        currentDate: '',
        Memuat...: true,
        refreshInterval: null,
        countdownInterval: null,
        
        init() {
            this.generateQrCode();
            this.updateClock();
            setInterval(() => this.updateClock(), 1000);
        },
        
        async generateQrCode() {
            this.Memuat... = true;
            
            try {
                const response = await fetch('{{ route("admin.qrcode.generate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        location_name: this.locationName,
                        latitude: null,
                        longitude: null
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    this.qrImage = result.data.qr_image;
                    this.expirySeconds = result.data.expiry_seconds;
                    this.startCountdown();
                }
            } catch (error) {
                console.error('Generate QR error:', error);
                setTimeout(() => this.generateQrCode(), 5000);
            } finally {
                this.Memuat... = false;
            }
        },
        
        startCountdown() {
            this.countdown = this.expirySeconds;
            
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
            }
            
            this.countdownInterval = setInterval(() => {
                this.countdown--;
                
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
        }
    }));
});
</script>
@endpush
@endsection
