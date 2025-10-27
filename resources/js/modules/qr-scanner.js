import { BrowserMultiFormatReader, NotFoundException } from '@zxing/library';

const defaultConfig = {
    verifyUrl: null,
    attendanceUrl: null,
    dashboardUrl: null,
};

document.addEventListener('alpine:init', () => {
    Alpine.data('qrScanner', (config = {}) => ({
        ...defaultConfig,
        ...config,

        codeReader: null,
        stream: null,

        scanning: false,
        scanSuccess: false,
        scanError: false,
        loading: false,

        qrData: null,
        successMessage: '',
        errorMessage: '',
        loadingMessage: '',

        init() {
            if (!this.verifyUrl || !this.attendanceUrl) {
                console.error('[qrScanner] Missing configuration. Please provide verifyUrl and attendanceUrl.');
                return;
            }

            this.codeReader = new BrowserMultiFormatReader();
            this.startCamera();
            window.addEventListener('beforeunload', () => this.destroy());
        },

        async startCamera() {
            try {
                this.loading = true;
                this.loadingMessage = 'Mengaktifkan kamera...';

                this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
                this.$refs.video.srcObject = this.stream;

                await this.$nextTick();
                await new Promise((resolve) => {
                    if (this.$refs.video.readyState >= 2) {
                        resolve();
                    } else {
                        this.$refs.video.addEventListener('loadeddata', () => resolve(), { once: true });
                    }
                });

                this.startScanning();
            } catch (error) {
                console.error('Camera error:', error);
                this.showError('Tidak dapat mengakses kamera. Pastikan izin kamera diberikan.');
            } finally {
                this.loading = false;
            }
        },

        startScanning() {
            if (!this.stream) {
                return;
            }

            this.scanning = true;
            this.loading = false;
            this.scanSuccess = false;
            this.scanError = false;

            this.codeReader.decodeFromVideoDevice(null, this.$refs.video, async (result, error, controls) => {
                if (result) {
                    controls.stop();
                    this.handleScanResult(result.getText());
                }

                if (error && !(error instanceof NotFoundException) && !String(error?.name).includes('NotFoundException')) {
                    console.error('Decode error:', error);
                }
            });
        },

        async handleScanResult(text) {
            try {
                this.loading = true;
                this.loadingMessage = 'Memverifikasi kode QR...';

                const data = JSON.parse(text);
                const verification = await this.verifyQr(data.token);

                this.qrData = { ...data, ...(verification.data ?? {}) };
                this.scanSuccess = true;
                this.successMessage =
                    verification.data?.location
                        ? `QR Code valid untuk lokasi ${verification.data.location}`
                        : verification.message ?? 'QR Code valid. Silakan lanjutkan proses absensi.';
            } catch (error) {
                console.error('Scan result error:', error);
                this.showError(error.message ?? 'QR Code tidak valid atau sudah kadaluarsa.');
            } finally {
                this.loading = false;
            }
        },

        async verifyQr(token) {
            const response = await fetch(this.verifyUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ token }),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message ?? 'QR Code tidak valid atau sudah kadaluarsa.');
            }

            return data;
        },

        async processCheckIn() {
            await this.processAttendance('check-in', 'Check-in');
        },

        async processCheckOut() {
            await this.processAttendance('check-out', 'Check-out');
        },

        async processAttendance(action, label) {
            if (!this.qrData) {
                this.showError('QR Code belum dipindai.');
                return;
            }

            this.loading = true;
            this.loadingMessage = 'Mengambil foto selfie...';

            try {
                const selfie = await this.captureSelfie();
                this.loadingMessage = `${label} sedang diproses...`;

                const location = await this.getLocation();

                const response = await fetch(`${this.attendanceUrl}/${action}`, {
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
                    (position) =>
                        resolve({
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
            if (this.dashboardUrl) {
                setTimeout(() => (window.location.href = this.dashboardUrl), 2500);
            }
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
            this.stream = null;
        },

        destroy() {
            this.stopCamera();
        },
    }));
});
