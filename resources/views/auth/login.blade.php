@extends('layouts.app')

@section('title', 'Masuk')

@section('fullWidth', true)

@section('hero')
<div class="relative overflow-hidden rounded-[32px] border border-white/20 bg-white/10 p-10 shadow-[0_45px_90px_-50px_rgba(15,23,42,0.65)] backdrop-blur-2xl">
    <div class="absolute inset-0 -z-10">
        <div class="absolute -top-16 -left-10 h-56 w-56 rounded-full bg-white/25 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/2 h-64 w-64 -translate-x-1/2 rounded-full bg-sky-400/30 blur-3xl"></div>
        <div class="absolute -bottom-20 -right-10 h-72 w-72 rounded-full bg-indigo-500/30 blur-3xl"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-white/10 via-white/5 to-transparent"></div>
    </div>
    <div class="relative grid gap-10 lg:grid-cols-2 lg:items-center">
        <div class="space-y-6">
            <span class="inline-flex items-center gap-2 rounded-full border border-white/40 bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white/90 backdrop-blur">
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
                <div class="rounded-2xl border border-white/30 bg-white/15 p-4 backdrop-blur">
                    <dt class="text-xs font-medium uppercase tracking-wide text-white/70">Kecepatan Pindai</dt>
                    <dd class="mt-2 text-2xl font-semibold text-white">0.8 detik</dd>
                </div>
                <div class="rounded-2xl border border-white/30 bg-white/15 p-4 backdrop-blur">
                    <dt class="text-xs font-medium uppercase tracking-wide text-white/70">Akurasi Geotag</dt>
                    <dd class="mt-2 text-2xl font-semibold text-white">±5 meter</dd>
                </div>
                <div class="rounded-2xl border border-white/30 bg-white/15 p-4 backdrop-blur">
                    <dt class="text-xs font-medium uppercase tracking-wide text-white/70">Penghematan Waktu</dt>
                    <dd class="mt-2 text-2xl font-semibold text-white">46%</dd>
                </div>
            </dl>
        </div>
        <div class="hidden lg:flex">
            <div class="relative mx-auto w-full max-w-lg">
                <div class="absolute -left-10 -top-6 h-36 w-36 rounded-full bg-white/20 blur-2xl"></div>
                <div class="absolute -right-16 bottom-0 h-40 w-40 rounded-full bg-emerald-400/40 blur-3xl"></div>
                <div class="relative overflow-hidden rounded-3xl border border-white/25 bg-white/10 p-8 shadow-2xl shadow-sky-500/10 backdrop-blur">
                    <div class="absolute inset-px rounded-[22px] border border-white/20"></div>
                    <h2 class="relative text-base font-semibold uppercase tracking-wide text-white/80">Sorotan Insight</h2>
                    <div class="relative mt-6 space-y-5 text-sm text-white/80">
                        <div class="rounded-2xl border border-white/20 bg-white/12 p-4 shadow-lg shadow-slate-900/10">
                            <p class="font-semibold text-white">Analitik Kehadiran Harian</p>
                            <p class="mt-2 text-xs text-white/70">Cek tren keterlambatan dan performa tim dengan insight instan.</p>
                        </div>
                        <div class="rounded-2xl border border-white/20 bg-white/12 p-4 shadow-lg shadow-slate-900/10">
                            <p class="font-semibold text-white">Penjadwalan Dinamis</p>
                            <p class="mt-2 text-xs text-white/70">Sesuaikan jadwal shift dan sinkronkan dengan kalender internal.</p>
                        </div>
                        <div class="rounded-2xl border border-white/20 bg-white/12 p-4 shadow-lg shadow-slate-900/10">
                            <p class="font-semibold text-white">Integrasi HRIS & Payroll</p>
                            <p class="mt-2 text-xs text-white/70">Hubungkan data absensi dengan sistem HR untuk payroll otomatis.</p>
                        </div>
                    </div>
                </div>
                <div class="pointer-events-none absolute -right-16 top-1/4 h-20 w-20 rounded-full bg-white/20 blur-2xl"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="relative space-y-24 overflow-hidden">
    <div class="pointer-events-none absolute inset-0">
        <div class="absolute -top-48 right-0 h-96 w-96 rounded-full bg-sky-400/15 blur-[200px]"></div>
        <div class="absolute top-1/3 -left-24 h-80 w-80 rounded-full bg-indigo-500/20 blur-[180px]"></div>
        <div class="absolute bottom-0 right-1/3 h-72 w-72 rounded-full bg-blue-500/20 blur-[160px]"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,420px)_minmax(0,1fr)]">
            <div class="relative overflow-hidden rounded-[28px] border border-white/30 bg-white/20 p-8 shadow-[0_35px_80px_-40px_rgba(15,23,42,0.45)] backdrop-blur-xl dark:border-slate-800/60 dark:bg-slate-900/60">
                <div class="pointer-events-none absolute inset-px rounded-[24px] border border-white/25 dark:border-slate-800/60"></div>
                <div class="relative space-y-6">
                    <div class="space-y-1">
                        <h2 class="text-2xl font-semibold text-slate-900 dark:text-slate-50">Masuk ke Presensia</h2>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Kelola absensi, cuti, dan produktivitas tim Anda di satu tempat dengan keamanan terjamin.</p>
                    </div>
                    <form method="POST" action="{{ route('login') }}" class="space-y-5">
                        @csrf
                        <div class="space-y-2">
                            <label for="email" class="text-sm font-medium text-slate-700 dark:text-slate-300">Email</label>
                            <div class="relative">
                                <input
                                    id="email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    required
                                    value="{{ old('email') }}"
                                    placeholder="nama@perusahaan.com"
                                    class="input rounded-2xl border-white/40 bg-white/50 pr-12 text-slate-900 shadow-inner shadow-white/30 backdrop-blur focus:border-blue-400 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-500 @error('email') border-rose-400/80 ring-rose-300/40 focus:border-rose-500 focus:ring-rose-300/60 dark:border-rose-500/60 dark:ring-rose-400/40 @enderror"
                                />
                                <div class="pointer-events-none absolute inset-0 rounded-2xl border border-white/40 dark:border-slate-800/70"></div>
                                <svg class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400 dark:text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 10-9 0 4.5 4.5 0 009 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 19.5l-3-3" />
                                </svg>
                            </div>
                            @error('email')
                                <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <label for="password" class="font-medium text-slate-700 dark:text-slate-300">Kata sandi</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-blue-500 hover:text-blue-400 dark:text-blue-300 dark:hover:text-blue-200">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>
                            <div class="relative" x-data="{ show: false }">
                                <input
                                    id="password"
                                    name="password"
                                    x-bind:type="show ? 'text' : 'password'"
                                    autocomplete="current-password"
                                    required
                                    placeholder="Masukkan kata sandi"
                                    class="input rounded-2xl border-white/40 bg-white/50 pr-12 text-slate-900 shadow-inner shadow-white/30 backdrop-blur focus:border-blue-400 focus:ring-blue-200 dark:border-slate-700 dark:bg-slate-950/60 dark:text-slate-100 dark:focus:border-blue-400 dark:focus:ring-blue-500 @error('password') border-rose-400/80 ring-rose-300/40 focus:border-rose-500 focus:ring-rose-300/60 dark:border-rose-500/60 dark:ring-rose-400/40 @enderror"
                                />
                                <div class="pointer-events-none absolute inset-0 rounded-2xl border border-white/40 dark:border-slate-800/70"></div>
                                <button type="button" class="absolute inset-y-0 right-3 inline-flex items-center text-slate-400 transition hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300" x-on:click="show = ! show">
                                    <span x-show="!show" x-cloak>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </span>
                                    <span x-show="show" x-cloak>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M9.88 9.88A3 3 0 0012 15a3 3 0 002.12-.88M6.16 6.16A9.71 9.71 0 003 12c1.274 4.057 5.065 7 9.542 7a9.71 9.71 0 005.121-1.485M12 7c.661 0 1.289.127 1.866.358M19.208 8.792A9.715 9.715 0 0121 12c-1.09 3.471-3.897 6.107-7.275 6.854" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs font-medium text-rose-500">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <label for="remember" class="inline-flex items-center gap-2 text-slate-600 dark:text-slate-400">
                                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 rounded border-white/40 bg-white/40 text-blue-600 focus:ring-blue-400 dark:border-slate-700 dark:bg-slate-900/70" {{ old('remember') ? 'checked' : '' }}>
                                Ingat saya
                            </label>
                            <span class="text-xs text-slate-500 dark:text-slate-500">Sesi aman 24 jam</span>
                        </div>
                        <div class="space-y-3">
                            <button type="submit" class="relative inline-flex w-full items-center justify-center overflow-hidden rounded-2xl border border-blue-500/40 bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-[0_20px_40px_-24px_rgba(37,99,235,0.85)] transition hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2">
                                <span class="absolute inset-0 rounded-2xl bg-white/15 opacity-0 transition group-hover:opacity-100"></span>
                                <span class="relative">Masuk</span>
                            </button>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-2xl border border-white/40 bg-white/40 px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-inner shadow-white/30 backdrop-blur transition hover:border-blue-200 hover:text-blue-600 dark:border-slate-700 dark:bg-slate-900/60 dark:text-slate-200 dark:hover:border-blue-400/40 dark:hover:text-blue-300">
                                    Buat akun baru
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-[28px] border border-white/30 bg-white/20 p-8 shadow-[0_35px_80px_-40px_rgba(15,23,42,0.35)] backdrop-blur-xl dark:border-slate-800/60 dark:bg-slate-900/55">
                <div class="pointer-events-none absolute inset-px rounded-[24px] border border-white/20 dark:border-slate-800/70"></div>
                <div class="relative space-y-8">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Kenapa Presensia dipercayai tim modern?</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Pengalaman absensi glassy yang responsif, aman, dan kaya insight untuk HR dan manager.</p>
                    </div>
                    <dl class="grid gap-4 text-sm text-slate-600 dark:text-slate-300">
                        <div class="flex items-start gap-3 rounded-2xl border border-white/20 bg-white/20 p-4 shadow-inner shadow-white/20 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60">
                            <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white shadow-lg shadow-blue-600/40">1</span>
                            <div>
                                <dt class="font-semibold text-slate-900 dark:text-slate-100">Analitik instan</dt>
                                <dd class="mt-1 text-xs">Tren keterlambatan, performa hadir, dan overtime terupdate tanpa spreadsheet manual.</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl border border-white/20 bg-white/20 p-4 shadow-inner shadow-white/20 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60">
                            <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white shadow-lg shadow-blue-600/40">2</span>
                            <div>
                                <dt class="font-semibold text-slate-900 dark:text-slate-100">Workflow perizinan</dt>
                                <dd class="mt-1 text-xs">Cuti, sakit, dan lembur dengan approval otomatis ke manager dan HR.</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3 rounded-2xl border border-white/20 bg-white/20 p-4 shadow-inner shadow-white/20 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60">
                            <span class="mt-1 inline-flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white shadow-lg shadow-blue-600/40">3</span>
                            <div>
                                <dt class="font-semibold text-slate-900 dark:text-slate-100">Geotag & multi-lokasi</dt>
                                <dd class="mt-1 text-xs">Penjadwalan shift dan zona lokasi memastikan kepatuhan lintas cabang.</dd>
                            </div>
                        </div>
                    </dl>
                    <div class="rounded-2xl border border-white/20 bg-white/20 p-4 text-xs text-slate-500 shadow-inner shadow-white/20 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60 dark:text-slate-400">
                        <p class="font-semibold text-slate-700 dark:text-slate-300">Scanner Mode Offline</p>
                        <p class="mt-1">Verifikasi QR, location fencing, dan device trust list tetap berjalan walau koneksi tidak stabil. Data akan tersinkron otomatis.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section id="fitur" class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[32px] border border-white/20 bg-white/10 p-10 shadow-[0_40px_90px_-50px_rgba(15,23,42,0.5)] backdrop-blur-2xl dark:border-slate-800/60 dark:bg-slate-900/60">
            <div class="grid gap-10 lg:grid-cols-[320px_minmax(0,1fr)]">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/20 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-700/80 dark:text-slate-200/80">Keunggulan</span>
                    <h2 class="text-3xl font-semibold text-slate-900 dark:text-slate-50">Satu platform, banyak lapisan perlindungan</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Presensia menggabungkan kecepatan pemindaian, verifikasi biometrik, dan insight data sehingga HR dapat mengambil keputusan berbasis fakta.</p>
                </div>
                <div class="grid gap-6 sm:grid-cols-2">
                    <div class="rounded-3xl border border-white/20 bg-white/20 p-6 shadow-lg shadow-sky-500/10 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Pemindaian Multi-layer</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">QR terenkripsi, deteksi device, dan selfie otomatis memastikan absensi sah.</p>
                    </div>
                    <div class="rounded-3xl border border-white/20 bg-white/20 p-6 shadow-lg shadow-emerald-500/10 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Dashboard Real-time</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Monitor kehadiran per divisi, rekap lembur, dan alert keterlambatan tanpa perlu export manual.</p>
                    </div>
                    <div class="rounded-3xl border border-white/20 bg-white/20 p-6 shadow-lg shadow-fuchsia-500/10 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Workflow Fleksibel</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Cut off jam kerja, permintaan lembur, dan notifikasi ke approval chain secara otomatis.</p>
                    </div>
                    <div class="rounded-3xl border border-white/20 bg-white/20 p-6 shadow-lg shadow-amber-500/10 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-100">Ekosistem Terhubung</h3>
                        <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">Integrasi mulus dengan HRIS, payroll, dan alat kolaborasi melalui API terbuka.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="alur" class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[32px] border border-white/20 bg-white/10 p-10 shadow-[0_45px_90px_-60px_rgba(15,23,42,0.55)] backdrop-blur-2xl dark:border-slate-800/60 dark:bg-slate-900/60">
            <div class="grid gap-8 lg:grid-cols-[280px_minmax(0,1fr)] lg:items-start">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/30 bg-white/20 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.3em] text-slate-700/80 dark:text-slate-200/80">Alur Kerja</span>
                    <h2 class="text-3xl font-semibold text-slate-900 dark:text-slate-50">Siklus absensi yang menyatu</h2>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Setiap tahapan dirancang dengan lapisan keamanan dan transparansi agar karyawan merasa nyaman dan HR mudah memantau.</p>
                </div>
                <div class="grid gap-5 sm:grid-cols-2">
                    @php
                        $steps = [
                            ['title' => 'Scan & Validasi', 'desc' => 'Karyawan memindai QR; sistem memverifikasi token, lokasi, dan selfie.'],
                            ['title' => 'Review Manager', 'desc' => 'Manager menerima notifikasi untuk menyetujui izin/lembur langsung dari dashboard.'],
                            ['title' => 'Insight Otomatis', 'desc' => 'Presensia menyusun metrik kehadiran, keterlambatan, dan jam lembur secara real-time.'],
                            ['title' => 'Integrasi Payroll', 'desc' => 'HR menarik data absensi bersih ke payroll & HRIS pilihan Anda.'],
                        ];
                    @endphp
                    @foreach ($steps as $index => $step)
                        <div class="rounded-3xl border border-white/20 bg-white/20 p-6 shadow-lg shadow-slate-900/10 backdrop-blur dark:border-slate-800/60 dark:bg-slate-900/60">
                            <span class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-white/40 bg-white/30 text-sm font-semibold text-slate-800 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-200">{{ $index + 1 }}</span>
                            <h3 class="mt-4 text-lg font-semibold text-slate-900 dark:text-slate-100">{{ $step['title'] }}</h3>
                            <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="integrasi" class="relative overflow-hidden border border-white/15 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900 py-20 text-white">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(59,130,246,0.18),transparent_55%)]"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
                <div class="space-y-4">
                    <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.3em] text-white/80">Integrasi Ekosistem</span>
                    <h2 class="text-3xl font-semibold leading-tight sm:text-4xl">Tersambung ke alat kerja favorit Anda</h2>
                    <p class="text-base text-white/70">Sinkronkan data Presensia dengan HRIS, payroll, dan alat kolaborasi untuk otomasi proses yang komprehensif.</p>
                    <ul class="space-y-3 text-sm text-white/70">
                        <li>• API terbuka untuk Talenta, Mekari, dan HRIS lain</li>
                        <li>• Notifikasi real-time via Slack, Teams, dan email</li>
                        <li>• Export dinamis ke Excel, Google Sheets, hingga PDF</li>
                    </ul>
                </div>
                <div class="rounded-3xl border border-white/15 bg-white/10 p-10 shadow-[0_30px_80px_-50px_rgba(59,130,246,0.45)] backdrop-blur-xl">
                    <h3 class="text-lg font-semibold text-white">Integrasi siap pakai</h3>
                    <div class="mt-6 grid gap-4 sm:grid-cols-2">
                        @foreach (['Kalender Google Workspace', 'SAP SuccessFactors', 'Jurnal Payroll', 'Microsoft Dynamics 365'] as $integration)
                            <div class="rounded-2xl border border-white/20 bg-white/15 p-4 text-sm text-white/80 shadow-inner shadow-white/10 backdrop-blur">
                                {{ $integration }}
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="testimoni" class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-semibold text-slate-900 dark:text-slate-50 sm:text-4xl">Cerita sukses dari organisasi inovatif</h2>
            <p class="mt-3 text-sm text-slate-600 dark:text-slate-400">Transparansi performa dan efisiensi proses HR yang terasa sejak minggu pertama implementasi.</p>
        </div>
        <div class="mt-10 grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @php
                $stories = [
                    ['quote' => '“Presensia mempercepat proses absensi cabang kami yang tersebar, sekaligus memberikan insight keterlibatan yang dibutuhkan tim HR.”', 'name' => 'Raka Prasetya', 'role' => 'Head of HR, Nusantara Retail'],
                    ['quote' => '“Integrasi payroll otomatis membantu tim keuangan menyelesaikan penggajian 3x lebih cepat tanpa risiko kesalahan data.”', 'name' => 'Indah Lestari', 'role' => 'Finance Lead, Harmoni Clinic'],
                    ['quote' => '“Dashboard manager membuat pemantauan performa tim jauh lebih transparan dan berbasis data real-time.”', 'name' => 'Bima Wicaksana', 'role' => 'Operations Manager, Orbit Logistics'],
                ];
            @endphp
            @foreach ($stories as $story)
                <div class="flex flex-col gap-4 rounded-3xl border border-white/20 bg-white/20 p-8 shadow-[0_30px_70px_-45px_rgba(15,23,42,0.35)] backdrop-blur-xl dark:border-slate-800/60 dark:bg-slate-900/60">
                    <p class="text-sm text-slate-600 dark:text-slate-300">{{ $story['quote'] }}</p>
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $story['name'] }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-500">{{ $story['role'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection






