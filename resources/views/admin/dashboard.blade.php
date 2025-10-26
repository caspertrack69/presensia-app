@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-semibold text-slate-900">Dashboard Administrator</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau performa sistem absensi secara menyeluruh.</p>
            </div>
            <div class="flex gap-2">
                <x-ui-button as="a" href="{{ route('admin.reports.attendance') }}" variant="outline">
                    Laporan Absensi
                </x-ui-button>
                <x-ui-button as="a" href="{{ route('admin.qrcode.display') }}">
                    Tampilkan QR Dinamis
                </x-ui-button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <x-ui-card>
                <x-ui-card-content>
                    <p class="text-sm font-medium text-slate-500">Total Karyawan</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $employeeCount }}</p>
                </x-ui-card-content>
            </x-ui-card>
            <x-ui-card>
                <x-ui-card-content>
                    <p class="text-sm font-medium text-slate-500">Total Manajer</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $managerCount }}</p>
                </x-ui-card-content>
            </x-ui-card>
            <x-ui-card>
                <x-ui-card-content>
                    <p class="text-sm font-medium text-slate-500">Pengguna Aktif</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $activeUsers }}</p>
                </x-ui-card-content>
            </x-ui-card>
            <x-ui-card>
                <x-ui-card-content>
                    <p class="text-sm font-medium text-slate-500">QR Aktif</p>
                    <p class="mt-2 text-3xl font-semibold text-slate-900">{{ $activeQrCodes }}</p>
                </x-ui-card-content>
            </x-ui-card>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <x-ui-card>
                <x-ui-card-header>
                    <x-ui-card-title>Rekap Kehadiran Hari Ini</x-ui-card-title>
                </x-ui-card-header>
                <x-ui-card-content>
                    <dl class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-500">Hadir</dt>
                            <dd class="text-2xl font-semibold text-emerald-600">{{ $attendanceSummary['present'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Terlambat</dt>
                            <dd class="text-2xl font-semibold text-amber-600">{{ $attendanceSummary['late'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Izin</dt>
                            <dd class="text-2xl font-semibold text-slate-900">{{ $attendanceSummary['leave'] }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-500">Alpa</dt>
                            <dd class="text-2xl font-semibold text-rose-600">{{ $attendanceSummary['absent'] }}</dd>
                        </div>
                    </dl>
                </x-ui-card-content>
            </x-ui-card>

            <x-ui-card>
                <x-ui-card-header>
                    <x-ui-card-title>Pengajuan Izin / Cuti</x-ui-card-title>
                    <x-ui-card-description>Pengajuan yang menunggu tindakan admin.</x-ui-card-description>
                </x-ui-card-header>
                <x-ui-card-content class="space-y-3">
                    @forelse ($pendingLeaveRequests as $request)
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm">
                            <p class="font-medium text-slate-900">{{ $request->user?->name }}</p>
                            <p class="text-xs text-slate-500">
                                {{ $request->start_date->format('d M') }} - {{ $request->end_date->format('d M Y') }} •
                                {{ ucfirst($request->type) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Tidak ada pengajuan menunggu.</p>
                    @endforelse
                </x-ui-card-content>
                <x-ui-card-footer>
                    <x-ui-button as="a" href="{{ route('admin.leave-requests.index') }}" size="sm" variant="ghost">
                        Kelola Pengajuan
                    </x-ui-button>
                </x-ui-card-footer>
            </x-ui-card>
        </div>

        <x-ui-card>
            <x-ui-card-header>
                <x-ui-card-title>Kehadiran Terbaru</x-ui-card-title>
            </x-ui-card-header>
            <x-ui-card-content class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check-out</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($recentAttendance as $attendance)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $attendance->user?->name }}
                                    </td>
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
                                            $variant = match($attendance->status) {
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
                                        <x-ui-badge :variant="$variant">{{ $label }}</x-ui-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Belum ada pencatatan kehadiran.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui-card-content>
        </x-ui-card>
    </div>
</div>
@endsection
