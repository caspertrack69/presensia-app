@extends('layouts.app')

@section('title', 'Dashboard Karyawan')

@section('content')
<div class="min-h-screen bg-slate-50">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col gap-4 px-4 py-6 sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-8">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Halo, {{ Auth::user()->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">Ringkasan kehadiran dan aksi cepat tersedia di sini.</p>
            </div>
            <div class="flex items-center gap-3">
                <x-ui-button as="a" href="{{ route('employee.attendance.scanner') }}" variant="primary">
                    Buka Scanner QR
                </x-ui-button>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <x-ui-button type="submit" variant="outline">
                        Logout
                    </x-ui-button>
                </form>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8 space-y-8">
        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <x-ui-card class="lg:col-span-2">
                <x-ui-card-header>
                    <x-ui-card-title>Absensi Hari Ini</x-ui-card-title>
                    <x-ui-card-description>Pastikan Anda melakukan check-in dan check-out tepat waktu.</x-ui-card-description>
                </x-ui-card-header>
                <x-ui-card-content class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-6">
                        <p class="text-sm font-medium text-slate-500">Check-in</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-900">
                            {{ optional($todayAttendance?->check_in)->format('H:i') ?? '--:--' }}
                        </p>
                        <p class="mt-3 inline-flex items-center gap-2 rounded-full bg-slate-200 px-3 py-1 text-xs font-medium text-slate-700">
                            Status:
                            @if ($todayAttendance && $todayAttendance->status === 'late')
                                <span class="text-amber-600">Terlambat</span>
                            @elseif ($todayAttendance)
                                <span class="text-emerald-600">Tepat Waktu</span>
                            @else
                                <span class="text-slate-600">Belum Check-in</span>
                            @endif
                        </p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-6">
                        <p class="text-sm font-medium text-slate-500">Check-out</p>
                        <p class="mt-2 text-3xl font-semibold text-slate-900">
                            {{ optional($todayAttendance?->check_out)->format('H:i') ?? '--:--' }}
                        </p>
                        <div class="mt-3">
                            @if ($todayAttendance && !$todayAttendance->check_out)
                                <x-ui-button as="a" href="{{ route('employee.attendance.scanner') }}" size="sm">
                                    Check-out Sekarang
                                </x-ui-button>
                            @elseif(!$todayAttendance)
                                <p class="text-xs text-slate-500">Lakukan check-in terlebih dahulu.</p>
                            @endif
                        </div>
                    </div>
                </x-ui-card-content>
                <x-ui-card-footer>
                    @if (!$todayAttendance)
                        <x-ui-button as="a" href="{{ route('employee.attendance.scanner') }}">
                            Check-in Sekarang
                        </x-ui-button>
                    @endif
                </x-ui-card-footer>
            </x-ui-card>

            <div class="space-y-4">
                <x-ui-card>
                    <x-ui-card-header>
                        <x-ui-card-title>Statistik Bulan Ini</x-ui-card-title>
                    </x-ui-card-header>
                    <x-ui-card-content>
                        <dl class="space-y-3">
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-slate-500">Total Hadir</dt>
                                <dd class="text-base font-semibold text-slate-900">{{ $summary['present'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-slate-500">Terlambat</dt>
                                <dd class="text-base font-semibold text-amber-600">{{ $summary['late'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-slate-500">Izin</dt>
                                <dd class="text-base font-semibold text-slate-900">{{ $summary['leave'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-slate-500">Sakit</dt>
                                <dd class="text-base font-semibold text-slate-900">{{ $summary['sick'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between">
                                <dt class="text-sm text-slate-500">Alpa</dt>
                                <dd class="text-base font-semibold text-rose-600">{{ $summary['absent'] }}</dd>
                            </div>
                        </dl>
                    </x-ui-card-content>
                </x-ui-card>

                <x-ui-card>
                    <x-ui-card-header>
                        <x-ui-card-title>Aksi Cepat</x-ui-card-title>
                    </x-ui-card-header>
                    <x-ui-card-content class="space-y-3">
                        <x-ui-button as="a" href="{{ route('employee.leave-requests.create') }}" class="w-full">
                            Ajukan Izin / Cuti
                        </x-ui-button>
                        <x-ui-button as="a" href="{{ route('employee.leave-requests.index') }}" variant="outline" class="w-full">
                            Lihat Pengajuan Saya
                        </x-ui-button>
                        <x-ui-button as="a" href="{{ route('employee.attendance.history') }}" variant="ghost" class="w-full">
                            Riwayat Absensi
                        </x-ui-button>
                    </x-ui-card-content>
                </x-ui-card>
            </div>
        </div>

        <x-ui-card>
            <x-ui-card-header>
                <x-ui-card-title>Riwayat Absensi Terbaru</x-ui-card-title>
            </x-ui-card-header>
            <x-ui-card-content class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check-out</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($recentAttendances as $attendance)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $attendance->date->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @php
                                            $badgeVariant = match($attendance->status) {
                                                'present' => 'success',
                                                'late' => 'warning',
                                                'leave' => 'default',
                                                'sick' => 'warning',
                                                default => 'danger',
                                            };
                                            $label = match($attendance->status) {
                                                'present' => 'Hadir',
                                                'late' => 'Terlambat',
                                                'leave' => 'Izin',
                                                'sick' => 'Sakit',
                                                default => 'Alpa',
                                            };
                                        @endphp
                                        <x-ui-badge :variant="$badgeVariant">{{ $label }}</x-ui-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Belum ada riwayat absensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui-card-content>
            <x-ui-card-footer class="justify-end">
                <x-ui-button as="a" href="{{ route('employee.attendance.history') }}" size="sm" variant="ghost">
                    Lihat Riwayat Lengkap
                </x-ui-button>
            </x-ui-card-footer>
        </x-ui-card>
    </main>
</div>
@endsection
