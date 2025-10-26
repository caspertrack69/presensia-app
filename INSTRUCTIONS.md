# PLATFORM ABSENSI QR CODE (LARAVEL & SHADCN UI)

## 1. Ringkasan Proyek

Kami bertujuan untuk membangun sebuah platform absensi berbasis web (website) yang modern, efisien, dan aman. Sistem ini akan menggunakan QR Code sebagai metode verifikasi absensi. Backend dan frontend dibangun menggunakan Laravel dengan Vite sebagai build tool, diimplementasikan menggunakan komponen Shadcn UI untuk tampilan yang bersih, modern, dan responsif.

## 2. Tujuan Utama

- Menggantikan sistem absensi manual dengan solusi digital yang akurat.
- Menyediakan data absensi real-time bagi administrator.
- Menciptakan user experience (UX) yang sederhana bagi pegawai.
- Memastikan sistem aman dari kecurangan (seperti "titip absen").

## 3. Teknologi Utama

- **Framework**: Laravel (Versi 11+)
- **Build Tool**: Vite
- **Frontend**: Blade Templates dengan Alpine.js untuk interaktivitas
- **Styling**: Tailwind CSS dengan komponen Shadcn UI
- **Database**: MySQL atau PostgreSQL
- **Deployment**: (Sebutkan target server Anda, misal: VPS, AWS, dll)

## 4. User Roles & Fitur Inti

Platform harus memiliki tiga level akses utama:

### A. Role: Pegawai (Karyawan)

- **Autentikasi**: Login & Logout (secure)
- **Dashboard**: Menampilkan ringkasan absensi (misal: total hadir, izin, alpa bulan ini) dan profil pengguna
- **Fitur Absensi (Core)**:
  - Tombol "Scan QR" yang akan membuka kamera (menggunakan Web API)
  - Melakukan scan QR Code yang disediakan oleh Admin
  - Memberikan feedback instan (sukses/gagal) saat check-in atau check-out
- **Riwayat Absensi**: Melihat log absensi pribadi (jam masuk, jam keluar, lokasi)
- **Pengajuan**: Fitur sederhana untuk mengajukan izin, sakit, atau cuti (dengan upload dokumen pendukung jika perlu)

### B. Role: Admin (HR/Super Admin)

- **Manajemen Pengguna**: CRUD (Create, Read, Update, Delete) untuk data pegawai, termasuk penetapan role (Pegawai/Manajer)
- **Manajemen Departemen/Lokasi**: Mengelola unit kerja atau lokasi kantor (jika kantor cabang)
- **Dashboard Analitik**: Melihat data absensi real-time (siapa yang sudah hadir, terlambat, atau alpa hari ini)
- **QR Code Generator**:
  - Kemampuan untuk generate QR Code
  - (Lihat Nilai Pembeda) QR Code ini idealnya dinamis
- **Manajemen Pengajuan**: Menyetujui (Approve) atau Menolak (Reject) pengajuan izin/cuti dari pegawai
- **Laporan (Reporting)**:
  - Generate laporan absensi (harian, mingguan, bulanan)
  - Fitur ekspor laporan ke format Excel atau PDF
- **Pengaturan**: Mengatur jam kerja, toleransi keterlambatan, dan hari libur

### C. Role: Pendukung (Manajer/Kepala Divisi)

- Memiliki subset fitur Admin, namun terbatas hanya untuk tim/divisi yang ia pimpin
- **Dashboard Tim**: Melihat status absensi anggota timnya secara real-time
- **Persetujuan Awal**: Menjadi peninjau pertama (level 1 approval) untuk pengajuan izin/cuti anggota timnya sebelum diteruskan ke Admin/HR
- **Laporan Tim**: Melihat dan mengekspor laporan absensi khusus untuk timnya

## 5. Nilai Pembeda (USP - Unique Selling Proposition)

Untuk membedakan platform ini dari aplikasi absensi QR standar, kita akan fokus pada keamanan dan validasi data untuk mencegah kecurangan:

### Dynamic QR Code (Anti-Screenshot)

- QR Code untuk absensi bukan QR statis yang dicetak di dinding
- Admin (atau perangkat "Kiosk" di lobi) akan menampilkan QR Code di layar (misal: tablet atau monitor)
- QR Code ini harus berubah (regenerate) setiap 30-60 detik
- **Tujuan**: Mencegah pegawai melakukan absensi dengan memotret QR Code dan mengirimkannya ke teman yang masih di rumah (titip absen via screenshot)

### Verifikasi Foto (Selfie Validation)

- Saat pegawai berhasil memindai QR, aplikasi tidak langsung mencatat "Hadir"
- Aplikasi akan otomatis mengaktifkan kamera depan dan meminta pegawai untuk mengambil foto selfie
- **Tujuan**: Memastikan bahwa orang yang memindai adalah benar-benar pegawai yang bersangkutan. Foto ini disimpan sebagai bukti absensi

### Validasi Geolocation (Opsional, tapi direkomendasikan)

- Selain scan QR dan selfie, sistem juga mencatat koordinat GPS (Latitude, Longitude) pegawai saat mereka melakukan absensi
- Admin dapat melihat geotagging ini di peta untuk memastikan absensi dilakukan di lokasi kantor yang ditentukan

## 6. Ekspektasi UI/UX

- Desain harus bersih, minimalis, dan profesional sesuai dengan estetika Shadcn UI
- Aplikasi harus fully responsive (Mobile-First), karena sebagian besar pegawai akan mengaksesnya melalui smartphone
- Interaksi harus cepat, dengan loading state yang jelas
- Hot Module Replacement (HMR) melalui Vite untuk pengembangan yang lebih cepat

## 7. Konfigurasi Teknis dengan Laravel Vite

### Struktur Project:
resources/
├── views/
│ ├── components/ (Blade components)
│ ├── layouts/
│ └── pages/
├── css/
│ └── app.css
└── js/
└── app.js (dengan Alpine.js untuk interaktivitas)


### Keuntungan Menggunakan Vite dengan Laravel:
- Development server yang sangat cepat
- Hot Module Replacement (HMR) untuk CSS dan JavaScript
- Build time yang optimal untuk production
- Optimized asset handling dan minification
- Support untuk modern JavaScript features tanpa konfigurasi kompleks