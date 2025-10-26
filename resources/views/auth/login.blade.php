@extends('layouts.app')

@section('title', 'Masuk')

@section('fullWidth', true)

@section('hero')
<div class="grid gap-10 lg:grid-cols-2 lg:items-center">
    <div class="space-y-6">
        <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/90">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-3.5 w-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Presensi real-time, satu sentuhan
        </span>
        <h1 class="text-3xl font-semibold leading-tight text-white sm:text-4xl lg:text-5xl lg:leading-snug">
            Transformasi Absensi QR untuk kinerja tim yang lebih gesit dan terukur.
        </h1>
        <p class="max-w-2xl text-base text-white/80 sm:text-lg">
            Presensia menghadirkan pemindaian QR yang cepat, pelacakan lokasi yang akurat, insight kehadiran otomatis, serta integrasi human resource yang siap pakai untuk organisasi modern.
        </p>
        <dl class="grid grid-cols-1 gap-6 sm:grid-cols-3">
            <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                <dt class="text-xs font-medium uppercase tracking-wide text-white/60">Kecepatan Pindai</dt>
                <dd class="mt-2 text-2xl font-semibold text-white">0.8 detik</dd>
            </div>
            <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                <dt class="text-xs font-medium uppercase tracking-wide text-white/60">Akurasi Geotag</dt>
                <dd class="mt-2 text-2xl font-semibold text-white">±5 meter</dd>
            </div>
            <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
                <dt class="text-xs font-medium uppercase tracking-wide text-white/60">Penghematan Waktu</dt>
                <dd class="mt-2 text-2xl font-semibold text-white">46%</dd>
            </div>
        </dl>
    </div>
    <div class="hidden lg:flex">
        <div class="relative mx-auto w-full max-w-lg">
            <div class="absolute -left-10 -top-10 h-32 w-32 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -right-10 bottom-0 h-32 w-32 rounded-full bg-sky-400/40 blur-3xl"></div>
            <div class="relative overflow-hidden rounded-3xl border border-white/20 bg-white/10 p-8 backdrop-blur shadow-2xl">
                <h2 class="text-base font-semibold uppercase tracking-wide text-white/80">Sorotan Insight</h2>
                <div class="mt-6 space-y-5 text-sm text-white/80">
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-4">
                        <p class="font-semibold text-white">Analitik Kehadiran Harian</p>
                        <p class="mt-2 text-xs text-white/70">Cek tren keterlambatan dan performa tim dengan insight instan.</p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-4">
                        <p class="font-semibold text-white">Penjadwalan Dinamis</p>
                        <p class="mt-2 text-xs text-white/70">Sesuaikan jadwal shift dan sinkronkan dengan kalender internal.</p>
                    </div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-4">
                        <p class="font-semibold text-white">Integrasi HRIS & Payroll</p>
                        <p class="mt-2 text-xs text-white/70">Hubungkan data absensi dengan sistem HR untuk payroll otomatis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,420px)_minmax(0,1fr)]">
            <div class="space-y-6 rounded-3xl bg-white p-8 shadow-xl shadow-blue-500/5 ring-1 ring-slate-100">
                <div>
                    <h2 class="text-2xl font-semibold text-slate-900">Masuk ke Presensia</h2>
                    <p class="mt-2 text-sm text-slate-500">Kelola absensi, cuti, dan produktivitas tim dalam satu dasbor terintegrasi.</p>
                </div>
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            class="input @error('email') border-rose-500 ring-rose-200 focus:border-rose-500 focus:ring-rose-200 @enderror"
                            placeholder="nama@perusahaan.com">
                        @error('email')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-2">
                        <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            autocomplete="current-password"
                            required
                            class="input @error('password') border-rose-500 ring-rose-200 focus:border-rose-500 focus:ring-rose-200 @enderror"
                            placeholder="Masukkan password Anda">
                        @error('password')
                            <p class="text-xs font-medium text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <label class="inline-flex items-center gap-2 text-slate-600">
                            <input
                                id="remember"
                                name="remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            Ingat saya
                        </label>
                        <a href="#fitur" class="text-sm font-medium text-blue-600 hover:text-blue-700">Lihat keunggulan</a>
                    </div>
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                        </svg>
                        Masuk Sekarang
                    </button>
                </form>
                <p class="text-xs text-slate-400">&copy; {{ now()->year }} Presensia. Tingkatkan disiplin dan produktivitas dengan pengalaman absensi berbasis QR yang terukur.</p>
            </div>
            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-8 shadow-lg shadow-slate-200/60">
                    <h3 class="text-lg font-semibold text-slate-900">Mengapa Presensia unggul?</h3>
                    <ul class="mt-4 space-y-4 text-sm text-slate-600">
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">1</span>
                            Analitik absensi otomatis memetakan tren keterlambatan, kehadiran, dan overtime tanpa spreadsheet manual.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">2</span>
                            Workflow cuti dan perizinan terintegrasi dengan notifikasi instan ke manager dan HR.
                        </li>
                        <li class="flex gap-3">
                            <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white">3</span>
                            Penjadwalan shift, geofencing, dan multi-lokasi memastikan kepatuhan absensi lintas cabang.
                        </li>
                    </ul>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow">
                        <p class="text-sm font-medium text-slate-500">Kepuasan HR</p>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">92%</p>
                        <p class="mt-2 text-xs text-slate-500">HR merasakan proses payroll lebih cepat dan akurat.</p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow">
                        <p class="text-sm font-medium text-slate-500">Waktu Verifikasi</p>
                        <p class="mt-3 text-2xl font-semibold text-slate-900">-63%</p>
                        <p class="mt-2 text-xs text-slate-500">Pengurangan waktu verifikasi absensi manual.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="fitur" class="bg-gradient-to-br from-white via-white to-blue-50 py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-slate-900 sm:text-3xl">Fitur unggulan Presensia untuk seluruh organisasi</h2>
            <div class="mt-10 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-lg">
                    <h3 class="text-lg font-semibold text-slate-900">Absensi QR Multi-Lokasi</h3>
                    <p class="mt-3 text-sm text-slate-600">Buat, bagikan, dan rotasi QR unik per lokasi dengan validasi otomatis untuk menghindari spoofing.</p>
                    <ul class="mt-4 space-y-2 text-xs text-slate-500">
                        <li>• Penjadwalan reset QR otomatis</li>
                        <li>• Validasi geotag & radius</li>
                        <li>• Mode offline dengan sinkronisasi</li>
                    </ul>
                </article>
                <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-lg">
                    <h3 class="text-lg font-semibold text-slate-900">Insight Kehadiran AI</h3>
                    <p class="mt-3 text-sm text-slate-600">Analisa kecenderungan keterlambatan dan produktivitas tim dengan rekomendasi tindakan otomatis.</p>
                    <ul class="mt-4 space-y-2 text-xs text-slate-500">
                        <li>• Segmentasi performa per divisi</li>
                        <li>• Peringatan absence tak wajar</li>
                        <li>• Laporan otomatis via email</li>
                    </ul>
                </article>
                <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-lg">
                    <h3 class="text-lg font-semibold text-slate-900">Pengalaman Karyawan Modern</h3>
                    <p class="mt-3 text-sm text-slate-600">Karyawan dapat melakukan check-in/out, mengajukan cuti, dan memantau saldo secara mobile.</p>
                    <ul class="mt-4 space-y-2 text-xs text-slate-500">
                        <li>• Riwayat absensi personal</li>
                        <li>• E-slip dan notifikasi real-time</li>
                        <li>• Dukungan multi-bahasa</li>
                    </ul>
                </article>
            </div>
        </div>
    </section>

    <section id="alur" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-slate-900 sm:text-3xl">Alur kerja end-to-end yang mulus</h2>
            <div class="mt-12 grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">1</span>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Generate QR</h3>
                    <p class="mt-2 text-sm text-slate-600">Admin membuat QR tersinkronisasi lokasi dan waktu.</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">2</span>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Scan & Validasi</h3>
                    <p class="mt-2 text-sm text-slate-600">Karyawan memindai via portal mobile, sistem validasi otomatis.</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">3</span>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Insight & Tindakan</h3>
                    <p class="mt-2 text-sm text-slate-600">Manager menerima insight keterlibatan real-time dan peringatan.</p>
                </div>
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-sm font-semibold text-white">4</span>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Integrasi Payroll</h3>
                    <p class="mt-2 text-sm text-slate-600">HR menghubungkan data absensi ke payroll dan HRIS.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="integrasi" class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 py-20 text-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/80">
                        Integrasi Ekosistem
                    </span>
                    <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">Tersambung ke alat kerja favorit Anda</h2>
                    <p class="text-base text-white/70">Sinkronkan data Presensia dengan HRIS, payroll, dan alat kolaborasi untuk otomasi proses yang komprehensif.</p>
                    <ul class="space-y-3 text-sm text-white/70">
                        <li>• Integrasi API terbuka untuk HRIS dan payroll (Talenta, Gadjian, Mekari)</li>
                        <li>• Notifikasi Slack, Microsoft Teams, dan email otomatis</li>
                        <li>• Export dinamis ke Excel, Google Sheets, dan PDF</li>
                    </ul>
                </div>
                <div class="rounded-3xl border border-white/10 bg-white/5 p-10 backdrop-blur">
                    <h3 class="text-lg font-semibold text-white">Integrasi siap pakai</h3>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-sm text-white/80">Kalender Google Workspace</div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-sm text-white/80">SAP SuccessFactors</div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-sm text-white/80">Jurnal Payroll</div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 text-sm text-white/80">Microsoft Dynamics 365</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimoni" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl font-semibold text-slate-900 sm:text-3xl">Cerita sukses dari perusahaan inovatif</h2>
            <div class="mt-10 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-8">
                    <p class="text-sm text-slate-600">“Presensia mempercepat proses absensi cabang kami yang tersebar, sekaligus memberikan insight keterlibatan yang kami butuhkan.”</p>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Raka Prasetya</p>
                        <p class="text-xs text-slate-500">Head of HR, Nusantara Retail</p>
                    </div>
                </div>
                <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-8">
                    <p class="text-sm text-slate-600">“Integrasi payroll otomatis membantu tim keuangan menyelesaikan penggajian 3x lebih cepat tanpa kesalahan data.”</p>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Indah Lestari</p>
                        <p class="text-xs text-slate-500">Finance Lead, Harmoni Clinic</p>
                    </div>
                </div>
                <div class="flex flex-col gap-4 rounded-3xl border border-slate-200 bg-slate-50 p-8">
                    <p class="text-sm text-slate-600">“Dashboard untuk manager membuat pemantauan performa tim jauh lebih transparan dan berbasis data.”</p>
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Bima Wicaksana</p>
                        <p class="text-xs text-slate-500">Operations Manager, Orbit Logistics</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
