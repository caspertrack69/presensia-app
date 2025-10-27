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

    <script>
        (() => {
            try {
                const stored = localStorage.getItem('presensia-theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const theme = stored === 'dark' || (!stored && prefersDark) ? 'dark' : 'light';
                document.documentElement.classList.toggle('dark', theme === 'dark');
                document.documentElement.style.colorScheme = theme;
            } catch (error) {
                console.error('Theme init error:', error);
            }
        })();
    </script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\View;

    $user = Auth::user();

    $navigationSections = [];
    $publicMenu = [];

    if ($user) {
        $dashboardRoute = $user->isAdmin()
            ? 'admin.dashboard'
            : ($user->isManager()
                ? 'manager.dashboard'
                : 'employee.dashboard');

        $coreMenu = [
            [
                'label' => 'Dashboard',
                'href' => route($dashboardRoute),
                'active' => request()->routeIs($dashboardRoute),
                'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3h7.5v7.5h-7.5zM12.75 3h7.5v7.5h-7.5zM12.75 12.75h7.5v7.5h-7.5zM3.75 12.75h7.5v7.5h-7.5z"/></svg>',
            ],
            [
                'label' => 'Scan QR',
                'href' => route('employee.attendance.scanner'),
                'active' => request()->routeIs('employee.attendance.scanner'),
                'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25V7.5A2.25 2.25 0 006.75 9.75H9M14.25 9.75H17.25A2.25 2.25 0 0019.5 7.5V5.25M19.5 18.75V16.5A2.25 2.25 0 0017.25 14.25H15M9 14.25H6.75A2.25 2.25 0 004.5 16.5v2.25"/></svg>',
            ],
            [
                'label' => 'Riwayat Absensi',
                'href' => route('employee.attendance.history'),
                'active' => request()->routeIs('employee.attendance.history'),
                'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
            ],
            [
                'label' => 'Cuti & Izin',
                'href' => route('employee.leave-requests.index'),
                'active' => request()->routeIs('employee.leave-requests.*'),
                'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5l9 4.5 9-4.5M4.5 19.5v-9M19.5 19.5v-9"/></svg>',
            ],
        ];

        $managerMenu = [];
        if ($user->isManager() || $user->isAdmin()) {
            $managerMenu = [
                [
                    'label' => 'Persetujuan Cuti',
                    'href' => route('manager.leave-requests.index'),
                    'active' => request()->routeIs('manager.leave-requests.*'),
                    'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                ],
                [
                    'label' => 'Laporan Tim',
                    'href' => route('manager.reports.attendance'),
                    'active' => request()->routeIs('manager.reports.attendance*'),
                    'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l6-6 4.5 4.5L21 4.5M3 20.25h18"/></svg>',
                ],
            ];
        }

        $adminMenu = [];
        if ($user->isAdmin()) {
            $adminMenu = [
                [
                    'label' => 'QR Aktif',
                    'href' => route('admin.qrcode.display'),
                    'active' => request()->routeIs('admin.qrcode.display'),
                    'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.5h7.5v7.5h-7.5zM12.75 4.5h7.5v3.75h-7.5zM12.75 11.25h3.75v3.75h-3.75zM16.5 15h3.75v3.75H16.5zM9 12.75v3.75H5.25v-3.75zM9 16.5h3.75v3.75H9z"/></svg>',
                ],
                [
                    'label' => 'Generate QR',
                    'href' => route('admin.qrcode.index'),
                    'active' => request()->routeIs('admin.qrcode.index') || request()->routeIs('admin.qrcode.current'),
                    'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M7 4v6M4 17h16M17 14v6M9 10l3 4 3-4"/></svg>',
                ],
                [
                    'label' => 'Manajemen Pengguna',
                    'href' => route('admin.users.index'),
                    'active' => request()->routeIs('admin.users.*'),
                    'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a6.75 6.75 0 0113.5 0v.75H4.5v-.75zM18.75 11.25a2.25 2.25 0 110 4.5"/></svg>',
                ],
                [
                    'label' => 'Departemen',
                    'href' => route('admin.departments.index'),
                    'active' => request()->routeIs('admin.departments.*'),
                    'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4.5 17.25v-7.5L12 3l7.5 6.75v7.5H4.5z"/></svg>',
                ],
                [
                    'label' => 'Laporan Master',
                    'href' => route('admin.reports.attendance'),
                    'active' => request()->routeIs('admin.reports.attendance*'),
                    'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5l6-6 4.5 4.5L21 4.5M3 20.25h18"/></svg>',
                ],
                [
                    'label' => 'Pengaturan',
                    'href' => route('admin.settings.index'),
                    'active' => request()->routeIs('admin.settings.*'),
                    'icon' => '<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h3l.75-2.25h4.5L18 6h3v3l-2.25.75v3L21 13.5v3h-3l-.75 2.25h-4.5L13.5 16.5h-3L9.75 18.75h-4.5L6 16.5H3v-3l2.25-.75v-3L3 9V6h3l.75-2.25h4.5L10.5 6zM12 14.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"/></svg>',
                ],
            ];
        }

        $navigationSections = array_filter([
            'Utama' => $coreMenu,
            'Manajerial' => $managerMenu,
            'Administrator' => $adminMenu,
        ]);
    } else {
        $publicMenu = [
            ['label' => 'Fitur Utama', 'href' => '#fitur'],
            ['label' => 'Cara Kerja', 'href' => '#alur'],
            ['label' => 'Integrasi', 'href' => '#integrasi'],
            ['label' => 'Testimoni', 'href' => '#testimoni'],
        ];
    }

    $containerClasses = View::hasSection('fullWidth')
        ? 'w-full'
        : 'mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8';
@endphp
<body x-data="layoutState()" x-init="init()" class="h-full antialiased bg-slate-100 text-slate-900 transition-colors dark:bg-slate-950 dark:text-slate-100">
    <div class="min-h-screen">
        @auth
            <div class="flex min-h-screen">
                <div x-show="sidebarOpen" class="fixed inset-0 z-40 flex lg:hidden" x-cloak>
                    <div class="fixed inset-0 bg-slate-900/60" @click="sidebarOpen = false" aria-hidden="true"></div>
                    <aside class="relative ml-4 mt-4 flex w-72 flex-col rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-2xl backdrop-blur dark:border-slate-800 dark:bg-slate-900/95">
                        <button type="button" class="absolute right-5 top-5 inline-flex h-8 w-8 items-center justify-center rounded-full border border-slate-200 text-slate-500 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800" @click="sidebarOpen = false">
                            <span class="sr-only">Tutup menu</span>
                            <svg class="h-4 w-4" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        @include('layouts.partials.sidebar', ['navigationSections' => $navigationSections, 'user' => $user, 'mobile' => true])
                    </aside>
                </div>

                <aside class="hidden w-72 border-r border-slate-200 bg-white/80 px-6 py-8 backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/70 lg:flex">
                    @include('layouts.partials.sidebar', ['navigationSections' => $navigationSections, 'user' => $user])
                </aside>

                <div class="flex flex-1 flex-col">
                    <header class="sticky top-0 z-30 border-b border-slate-200 bg-white/70 backdrop-blur dark:border-slate-800 dark:bg-slate-900/60">
                        <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                            <div class="flex items-center gap-3">
                                <button type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 lg:hidden" @click="sidebarOpen = true">
                                    <span class="sr-only">Buka menu</span>
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                    </svg>
                                </button>
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 dark:text-slate-500">Aktif</p>
                                    <p class="text-lg font-semibold text-slate-900 dark:text-slate-100">@yield('title')</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <button type="button" @click="toggleTheme()" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800 lg:hidden">
                                    <span class="sr-only">Ganti tema</span>
                                    <span x-show="theme === 'light'" x-cloak>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5M12 19.5V21M4.5 12H3M21 12h-1.5M5.47 5.47l1.06 1.06M17.47 17.47l1.06 1.06M18.53 5.47l-1.06 1.06M6.53 17.47l-1.06 1.06M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                                        </svg>
                                    </span>
                                    <span x-show="theme === 'dark'" x-cloak>
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.75A9.75 9.75 0 1111.25 3 7.5 7.5 0 0021 12.75z" />
                                        </svg>
                                    </span>
                                </button>
                                <div class="hidden text-right lg:block">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ \Illuminate\Support\Str::of($user->name)->limit(26) }}</p>
                                    <p class="text-xs font-medium uppercase tracking-wide text-blue-600 dark:text-blue-400">{{ $user->role }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="lg:hidden">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <main class="flex-1 space-y-6 px-4 py-8 sm:px-6 lg:px-8">
                        @hasSection('hero')
                            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-blue-600 via-blue-500 to-blue-700 text-white shadow-xl dark:border-slate-800">
                                <div class="px-6 py-12 sm:px-8">
                                    @yield('hero')
                                </div>
                            </section>
                        @endif

                        @hasSection('pre-content')
                            <div class="{{ $containerClasses }}">
                                @yield('pre-content')
                            </div>
                        @endif

                        <div class="{{ $containerClasses }}">
                            @yield('content')
                        </div>
                    </main>

                    <footer class="border-t border-slate-200 bg-white/80 px-4 py-6 text-sm text-slate-600 backdrop-blur sm:px-6 lg:px-8 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-400">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-slate-100">{{ config('app.name', 'Presensia') }}</p>
                                <p class="mt-1 text-xs text-slate-500 dark:text-slate-500">&copy; {{ now()->year }} Presensia. Platform absensi QR yang mendukung produktivitas tim modern.</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-4 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-500">
                                <span>Pemantauan Real-time</span>
                                <span>&middot;</span>
                                <span>Insight Keaktifan</span>
                                <span>&middot;</span>
                                <span>Integrasi HRIS</span>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>
        @else
            <div class="flex min-h-screen flex-col">
                <header class="border-b border-slate-200 bg-white/80 backdrop-blur dark:border-slate-800 dark:bg-slate-900/70">
                    <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-5 sm:px-6 lg:px-8">
                        <a href="{{ route('login') }}" class="flex items-center gap-2">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-lg font-semibold text-white shadow-sm">PR</span>
                            <div>
                                <p class="text-base font-semibold text-slate-900 dark:text-slate-100">{{ config('app.name', 'Presensia') }}</p>
                                <p class="text-xs font-medium uppercase tracking-wide text-blue-600 dark:text-blue-400">Smart Attendance Platform</p>
                            </div>
                        </a>
                        <nav class="hidden items-center gap-6 md:flex">
                            @foreach ($publicMenu as $item)
                                <a href="{{ $item['href'] }}" class="text-sm font-medium text-slate-600 transition hover:text-blue-600 dark:text-slate-300 dark:hover:text-blue-400">
                                    {{ $item['label'] }}
                                </a>
                            @endforeach
                        </nav>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="toggleTheme()" class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-600 transition hover:bg-slate-100 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">
                                <span class="sr-only">Ganti tema</span>
                                <span x-show="theme === 'light'" x-cloak>
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1.5M12 19.5V21M4.5 12H3M21 12h-1.5M5.47 5.47l1.06 1.06M17.47 17.47l1.06 1.06M18.53 5.47l-1.06 1.06M6.53 17.47l-1.06 1.06M12 7.5a4.5 4.5 0 100 9 4.5 4.5 0 000-9z" />
                                    </svg>
                                </span>
                                <span x-show="theme === 'dark'" x-cloak>
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.75A9.75 9.75 0 1111.25 3 7.5 7.5 0 0021 12.75z" />
                                    </svg>
                                </span>
                            </button>
                            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Masuk
                            </a>
                        </div>
                    </div>
                </header>

                @hasSection('hero')
                    <section class="border-b border-slate-200 bg-gradient-to-br from-blue-600 via-blue-500 to-blue-700 text-white dark:border-slate-800">
                        <div class="mx-auto w-full max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                            @yield('hero')
                        </div>
                    </section>
                @endif

                <main class="flex-1 py-12">
                    @hasSection('pre-content')
                        <div class="{{ $containerClasses }} mb-6">
                            @yield('pre-content')
                        </div>
                    @endif
                    <div class="{{ $containerClasses }}">
                        @yield('content')
                    </div>
                </main>

                <footer class="border-t border-slate-200 bg-white/80 px-4 py-6 text-sm text-slate-600 backdrop-blur sm:px-6 lg:px-8 dark:border-slate-800 dark:bg-slate-900/60 dark:text-slate-400">
                    <div class="mx-auto flex w-full max-w-7xl flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="font-semibold text-slate-900 dark:text-slate-100">{{ config('app.name', 'Presensia') }}</p>
                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-500">&copy; {{ now()->year }} Presensia. Platform absensi QR yang mendukung produktivitas tim modern.</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-4 text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-500">
                            <span>Monitoring Real-time</span>
                            <span>&middot;</span>
                            <span>Insight Kehadiran</span>
                            <span>&middot;</span>
                            <span>Integrasi HRIS</span>
                        </div>
                    </div>
                </footer>
            </div>
        @endauth
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('layoutState', () => ({
                theme: 'light',
                sidebarOpen: false,
                init() {
                    const stored = localStorage.getItem('presensia-theme');
                    if (stored === 'dark' || (stored === null && document.documentElement.classList.contains('dark'))) {
                        this.theme = 'dark';
                    }
                    this.applyTheme(this.theme);
                },
                toggleTheme() {
                    this.setTheme(this.theme === 'dark' ? 'light' : 'dark');
                },
                setTheme(theme) {
                    this.theme = theme;
                    this.applyTheme(theme);
                    localStorage.setItem('presensia-theme', theme);
                },
                applyTheme(theme) {
                    document.documentElement.classList.toggle('dark', theme === 'dark');
                    document.documentElement.style.colorScheme = theme;
                },
            }));
        });
    </script>

    @stack('scripts')
</body>
</html>
