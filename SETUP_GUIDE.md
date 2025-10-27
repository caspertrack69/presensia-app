# Platform Absensi QR Code - Presensia

Platform absensi berbasis QR Code yang modern, aman, dan efisien dengan fitur Dynamic QR, Selfie Validation, dan Geolocation Verification.

## Pembaruan Terbaru (27 Oktober 2025)

- Antarmuka login diperbarui dengan style glassmorphism lengkap dengan blur, highlight, dan tombol CTA responsif.
- Sidebar dan komponen UI dikompakkan agar tampil konsisten pada mode terang & gelap.
- Modul pemindaian QR dipindahkan ke `resources/js/modules/qr-scanner.js`, memastikan ZXing dan lifecycle kamera dikelola di satu tempat.
- Penggunaan Tailwind beralih ke `darkMode: 'class'` dan ditambahkan `color-scheme` agar warna native browser selaras dengan tema.
- Link `password.request` dan `register` kini opsional, hanya tampil bila rute tersedia.

> Setelah menarik perubahan terbaru, jalankan `npm run dev` atau `npm run build` ulang agar Vite memproses utilitas Tailwind yang baru.

## Fitur Utama

### 🔐 Keamanan
- **Dynamic QR Code**: QR Code berubah otomatis setiap 30-60 detik untuk mencegah screenshot
- **Selfie Validation**: Wajib mengambil foto selfie saat absensi untuk verifikasi identitas
- **Geolocation Verification**: Validasi lokasi GPS untuk memastikan absensi di kantor

### 👥 Multi-Role System
- **Employee**: Check-in/out, lihat riwayat absensi, pengajuan izin/cuti
- **Manager**: Dashboard tim, approval izin level 1, laporan tim
- **Admin**: Manajemen pengguna, generate QR Code, analytics, approval akhir

### 📱 Mobile-First Design
- Responsive design dengan Tailwind CSS
- Optimized untuk smartphone
- Support camera API untuk QR scanning

## Tech Stack

- **Backend**: Laravel 11+
- **Frontend**: Blade Templates, Alpine.js, Tailwind CSS
- **Build Tool**: Vite
- **Database**: MySQL/PostgreSQL
- **QR Code**: SimpleSoftwareIO/SimpleQRCode
- **QR Scanner**: ZXing Library

## Instalasi

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/PostgreSQL

### Setup Instructions

1. **Clone repository dan install dependencies**
   ```bash
   cd c:\laragon\www\presensia-app
   composer install
   npm install
   ```

2. **Setup environment**
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

3. **Konfigurasi database di file `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=presensia_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan migrations dan seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Build assets**
   ```bash
   npm run build
   # atau untuk development
   npm run dev
   ```

6. **Create storage link**
   ```bash
   php artisan storage:link
   ```

7. **Jalankan development server**
   ```bash
   php artisan serve
   ```

8. **Akses aplikasi**
   - URL: http://localhost:8000

## Default Credentials

### Admin
- Email: `admin@presensia.com`
- Password: `password`

### Manager
- Email: `manager@presensia.com`
- Password: `password`

### Employee
- Email: `employee@presensia.com`
- Password: `password`

## Struktur Database

### Tables
- `users` - Data pengguna dengan role (employee, manager, admin)
- `departments` - Data departemen/divisi
- `attendances` - Record absensi (check-in, check-out, selfie, geolocation)
- `leave_requests` - Pengajuan izin/cuti dengan approval workflow
- `qr_codes` - Dynamic QR codes dengan expiry time
- `settings` - Pengaturan aplikasi (jam kerja, toleransi, dll)

## Cara Penggunaan

### Untuk Employee

1. **Login** dengan akun employee
2. Di dashboard, klik **"Check In Sekarang"** atau **"Scan QR"**
3. **Beri izin** akses kamera dan lokasi GPS
4. **Arahkan kamera** ke QR Code di layar kiosk/monitor
5. Setelah QR terdeteksi, aplikasi otomatis **mengambil foto selfie**
6. Klik **"Check In"** atau **"Check Out"**
7. Tunggu konfirmasi berhasil

### Untuk Admin

1. **Login** dengan akun admin
2. Buka menu **QR Code Display** (`/admin/qrcode/display`)
3. QR Code akan otomatis **generate dan refresh** setiap 60 detik
4. **Tampilkan QR di monitor/tablet** di area check-in
5. Employee dapat melakukan scan dari smartphone mereka

### QR Code Display (Kiosk Mode)

Untuk menampilkan QR Code di kiosk/monitor:
- URL: `/admin/qrcode/display`
- Full screen di browser
- QR Code auto-refresh setiap expiry time
- Menampilkan waktu, tanggal, dan countdown

## Konfigurasi Settings

Settings dapat diubah melalui database table `settings`:

```sql
-- Jam kerja
work_start_time = '09:00:00'
work_end_time = '17:00:00'

-- Toleransi keterlambatan (menit)
late_threshold_minutes = 15

-- Expiry QR Code (detik)
qr_code_expiry_seconds = 60

-- Jarak maksimal dari kantor (meter)
max_distance_meters = 100

-- Koordinat kantor
office_latitude = '-6.200000'
office_longitude = '106.816666'

-- Toggle fitur
require_selfie = true
require_geolocation = true
```

## API Endpoints

### Authentication
- `POST /login` - Login
- `POST /logout` - Logout

### Employee Attendance
- `GET /employee/attendance/scanner` - QR Scanner page
- `POST /employee/attendance/verify-qr` - Verify QR code
- `POST /employee/attendance/check-in` - Check-in
- `POST /employee/attendance/check-out` - Check-out
- `GET /employee/attendance/history` - Attendance history

### Admin QR Code
- `GET /admin/qrcode/display` - QR display page
- `POST /admin/qrcode/generate` - Generate new QR
- `GET /admin/qrcode/current` - Get current active QR

## Development

### Run Development Server
```bash
# Terminal 1: Laravel
php artisan serve

# Terminal 2: Vite (HMR)
npm run dev
```

### Build for Production
```bash
npm run build
```

### Run Tests
```bash
php artisan test
```

## Security Features

### 1. Dynamic QR Code
- Token unik setiap generate
- Auto-expire setelah waktu tertentu
- Tidak bisa digunakan ulang setelah expired

### 2. Selfie Validation
- Menggunakan getUserMedia API
- Capture langsung dari kamera
- Disimpan sebagai bukti absensi

### 3. Geolocation
- Validasi jarak dari koordinat kantor
- Haversine formula untuk akurasi
- Konfigurable max distance

### 4. Role-Based Access Control
- Middleware `role:employee,manager,admin`
- Route protection per role
- Authorization policies

## Browser Support

- Chrome/Edge (recommended)
- Firefox
- Safari (iOS 11+)

**Catatan**: Camera API dan Geolocation memerlukan HTTPS di production.

## Troubleshooting

### Camera tidak bisa diakses
- Pastikan browser memiliki permission untuk kamera
- Di localhost, camera API bisa berjalan di HTTP
- Di production, wajib menggunakan HTTPS

### QR Code tidak ter-generate
- Cek apakah package `simplesoftwareio/simple-qrcode` sudah terinstall
- Run `composer dump-autoload`
- Pastikan GD/Imagick extension aktif

### Geolocation tidak akurat
- Pastikan GPS aktif di device
- Geolocation API lebih akurat di outdoor
- Sesuaikan `max_distance_meters` di settings

## Roadmap

- [ ] Export laporan ke Excel/PDF
- [ ] Push notifications
- [ ] Face recognition integration
- [ ] Multiple office locations
- [ ] Shift management
- [ ] Overtime tracking

## License

Proprietary - All rights reserved

## Support

Untuk bantuan dan pertanyaan, hubungi tim development.

---

Built with ❤️ using Laravel & Vite
