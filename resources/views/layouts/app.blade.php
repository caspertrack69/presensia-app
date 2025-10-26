<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Presensia') }} - @yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\View;

    $user = Auth::user();

    $primaryMenu = [];

    if ($user) {
        $primaryMenu[] = [
            'label' => 'Dashboard',
            'href' => $user->isAdmin()
                ? route('admin.dashboard')
                : ($user->isManager()
                    ? route('manager.dashboard')
                    : route('employee.dashboard')),
        ];

        $primaryMenu[] = [
            'label' => 'Scan QR',
            'href' => route('employee.attendance.scanner'),
        ];

        $primaryMenu[] = [
            'label' => 'Riwayat Absensi',
            'href' => route('employee.attendance.history'),
        ];

        $primaryMenu[] = [
            'label' => 'Cuti & Izin',
            'href' => route('employee.leave-requests.index'),
        ];

        if ($user->isManager() || $user->isAdmin()) {
            $primaryMenu[] = [
                'label' => 'Persetujuan Cuti',
                'href' => route('manager.leave-requests.index'),
            ];
            $primaryMenu[] = [
                'label' => 'Laporan Tim',
                'href' => route('manager.reports.attendance'),
            ];
        }

        if ($user->isAdmin()) {
            $primaryMenu[] = [
                'label' => 'QR Aktif',
                'href' => route('admin.qrcode.display'),
            ];
            $primaryMenu[] = [
                'label' => 'Generate QR',
                'href' => route('admin.qrcode.index'),
            ];
            $primaryMenu[] = [
                'label' => 'Manajemen Pengguna',
                'href' => route('admin.users.index'),
            ];
            $primaryMenu[] = [
                'label' => 'Departemen',
                'href' => route('admin.departments.index'),
            ];
            $primaryMenu[] = [
                'label' => 'Pengaturan',
                'href' => route('admin.settings.index'),
            ];
            $primaryMenu[] = [
                'label' => 'Laporan Master',
                'href' => route('admin.reports.attendance'),
            ];
        }
    } else {
        $primaryMenu = [
            ['label' => 'Fitur Utama', 'href' => '#fitur'],
            ['label' => 'Cara Kerja', 'href' => '#alur'],
            ['label' => 'Integrasi', 'href' => '#integrasi'],
            ['label' => 'Testimoni', 'href' => '#testimoni'],
        ];
    }

    $containerClasses = View::hasSection('fullWidth')
        ? 'w-full'
        : 'mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8';
@endphp
<body class="h-full bg-slate-50 antialiased text-slate-900">
    <div class="flex min-h-full flex-col">
        <header x-data="{ open: false }" class="border-b border-slate-200 bg-white/80 backdrop-blur">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <a href="{{ $user ? ($user->isAdmin() ? route('admin.dashboard') : ($user->isManager() ? route('manager.dashboard') : route('employee.dashboard'))) : route('login') }}" class="flex items-center gap-2">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-blue-600 text-lg font-semibold text-white shadow-sm">
                            PR
                        </span>
                        <div>
                            <p class="text-base font-semibold text-slate-900">{{ config('app.name', 'Presensia') }}</p>
                            <p class="text-xs font-medium uppercase tracking-wide text-blue-600">Smart Attendance Platform</p>
                        </div>
                    </a>
                    <nav class="hidden items-center gap-4 lg:flex">
                        @foreach ($primaryMenu as $item)
                            <a href="{{ $item['href'] }}" class="text-sm font-medium text-slate-600 transition hover:text-blue-600">
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <div class="hidden items-center gap-4 md:flex">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-800">{{ \Illuminate\Support\Str::of($user->name)->limit(24) }}</p>
                                <p class="text-xs font-medium uppercase tracking-wide text-blue-600">
                                    {{ $user->role }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                    </svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    @endauth
                    @guest
                        <div class="hidden items-center gap-3 md:flex">
                            <a href="#fitur" class="text-sm font-medium text-slate-600 transition hover:text-blue-600">Lihat Demo</a>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Masuk
                            </a>
                        </div>
                    @endguest
                    <button @click="open = !open" class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-600 transition hover:bg-slate-50 lg:hidden" type="button">
                        <span class="sr-only">Toggle menu</span>
                        <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg x-show="open" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div x-show="open" x-transition class="border-t border-slate-200 bg-white lg:hidden">
                <div class="space-y-1 px-4 py-4 sm:px-6">
                    @foreach ($primaryMenu as $item)
                        <a href="{{ $item['href'] }}" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-blue-50 hover:text-blue-600">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    @auth
                        <div class="mt-3 flex items-center justify-between rounded-lg bg-slate-50 px-3 py-3">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ \Illuminate\Support\Str::of($user->name)->limit(24) }}</p>
                                <p class="text-xs font-medium uppercase tracking-wide text-blue-600">{{ $user->role }}</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="mt-3 inline-flex w-full items-center justify-center rounded-full bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Masuk ke Presensia
                        </a>
                    @endauth
                </div>
            </div>
        </header>

        @hasSection('hero')
            <section class="border-b border-slate-200 bg-gradient-to-br from-blue-600 via-blue-500 to-blue-700 text-white">
                <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                    @yield('hero')
                </div>
            </section>
        @endif

        <main class="flex-1 py-8">
            @hasSection('pre-content')
                <div class="{{ $containerClasses }} mb-6">
                    @yield('pre-content')
                </div>
            @endif
            <div class="{{ $containerClasses }}">
                @yield('content')
            </div>
        </main>

        <footer class="border-t border-slate-200 bg-white">
            <div class="mx-auto flex w-full max-w-7xl flex-col gap-6 px-4 py-8 text-sm text-slate-600 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <div>
                    <p class="font-semibold text-slate-900">{{ config('app.name', 'Presensia') }}</p>
                    <p class="mt-1 text-xs text-slate-500">&copy; {{ now()->year }} Presensia. Platform absensi QR yang mendukung produktivitas tim modern.</p>
                </div>
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium uppercase tracking-wide text-slate-500">
                    <span>Pemantauan Real-time</span>
                    <span>&middot;</span>
                    <span>Insight Keaktifan</span>
                    <span>&middot;</span>
                    <span>Integrasi HRIS</span>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>
</html>
