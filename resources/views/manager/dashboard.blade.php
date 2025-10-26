@extends('layouts.app')

@section('title', 'Dashboard Manajer')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Dashboard Tim</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau kehadiran dan pengajuan izin tim Anda secara real-time.</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <select name="department_id" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                    @forelse ($departments as $department)
                        <option value="{{ $department->id }}" @selected(optional($selectedDepartment)->id === $department->id)>
                            {{ $department->name }}
                        </option>
                    @empty
                        <option value="">Belum ada departemen</option>
                    @endforelse
                </select>
                <x-ui.button type="submit" variant="secondary">Terapkan</x-ui.button>
            </form>
        </div>

        @if(!$selectedDepartment)
            <div class="rounded-lg border border-slate-200 bg-white px-4 py-6 text-center text-sm text-slate-500">
                Silakan pilih departemen untuk menampilkan data.
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>{{ $selectedDepartment->name }}</x-ui.card-title>
                        <x-ui.card-description>Total anggota aktif di tim ini.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <p class="text-4xl font-semibold text-slate-900">{{ $selectedDepartment->users->count() }}</p>
                        <p class="mt-2 text-xs text-slate-500">Termasuk karyawan dengan status aktif.</p>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Rekap Hari Ini</x-ui.card-title>
                        <x-ui.card-description>Status kehadiran tim pada {{ now()->translatedFormat('d F Y') }}.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content>
                        <dl class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-slate-500">Hadir</dt>
                                <dd class="text-xl font-semibold text-emerald-600">{{ $attendanceStats['present'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">Terlambat</dt>
                                <dd class="text-xl font-semibold text-amber-600">{{ $attendanceStats['late'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">Izin</dt>
                                <dd class="text-xl font-semibold text-slate-900">{{ $attendanceStats['leave'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">Alpa</dt>
                                <dd class="text-xl font-semibold text-rose-600">{{ $attendanceStats['absent'] }}</dd>
                            </div>
                        </dl>
                    </x-ui.card-content>
                </x-ui.card>

                <x-ui.card>
                    <x-ui.card-header>
                        <x-ui.card-title>Pengajuan Menunggu</x-ui.card-title>
                        <x-ui.card-description>Permintaan izin/cuti terbaru dari anggota tim.</x-ui.card-description>
                    </x-ui.card-header>
                    <x-ui.card-content class="space-y-3">
                        @forelse ($recentLeaveRequests as $request)
                            <div class="rounded-lg border border-slate-200 bg-slate-50 px-3 py-2">
                                <p class="text-sm font-medium text-slate-900">{{ $request->user?->name }}</p>
                                <p class="text-xs text-slate-600">
                                    {{ $request->start_date->format('d M') }} - {{ $request->end_date->format('d M Y') }} •
                                    {{ ucfirst($request->type) }}
                                </p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Tidak ada pengajuan menunggu.</p>
                        @endforelse
                    </x-ui.card-content>
                    <x-ui.card-footer>
                        <x-ui.button as="a" href="{{ route('manager.leave-requests.index') }}" size="sm" variant="ghost">
                            Kelola Pengajuan
                        </x-ui.button>
                    </x-ui.card-footer>
                </x-ui.card>
            </div>

            <x-ui.card>
                <x-ui.card-header>
                    <x-ui.card-title>Kehadiran Hari Ini</x-ui.card-title>
                </x-ui.card-header>
                <x-ui.card-content class="p-0">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check-in</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check-out</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                @forelse ($todayAttendance as $attendance)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $attendance->user?->name }}</td>
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
                                            <x-ui.badge :variant="$variant">{{ $label }}</x-ui.badge>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-6 text-center text-sm text-slate-500">
                                            Belum ada data kehadiran hari ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-ui.card-content>
            </x-ui.card>
        @endif
    </div>
</div>
@endsection
