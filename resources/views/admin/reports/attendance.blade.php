@extends('layouts.app')

@section('title', 'Laporan Kehadiran')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Laporan Kehadiran</h1>
                <p class="mt-1 text-sm text-slate-500">Filter dan ekspor data kehadiran karyawan sesuai kebutuhan.</p>
            </div>
        </div>

        <x-ui.card>
            <x-ui.card-content>
                <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4 md:items-end">
                    <div class="space-y-2">
                        <label for="department_id" class="text-sm font-medium text-slate-700">Departemen</label>
                        <select id="department_id" name="department_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            <option value="">Semua Departemen</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}" @selected($departmentId == $dept->id)>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label for="start_date" class="text-sm font-medium text-slate-700">Tanggal Mulai</label>
                        <input id="start_date" type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                    </div>
                    <div class="space-y-2">
                        <label for="end_date" class="text-sm font-medium text-slate-700">Tanggal Selesai</label>
                        <input id="end_date" type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                    </div>
                    <div class="flex gap-2">
                        <x-ui.button type="submit" class="flex-1">Terapkan</x-ui.button>
                        <x-ui.button as="a"
                                     href="{{ route('admin.reports.attendance.export', ['department_id' => $departmentId, 'start_date' => $startDate, 'end_date' => $endDate]) }}"
                                     variant="outline"
                                     class="flex-1">
                            Ekspor Excel
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-content class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Departemen</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check-in</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check-out</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($attendances as $attendance)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $attendance->date->translatedFormat('d F Y') }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $attendance->user?->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $attendance->user?->department?->name ?? '-' }}</td>
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
                                        <x-ui.badge :variant="$variant">{{ $label }}</x-ui.badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Tidak ada data kehadiran untuk filter ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card-content>
            <x-ui.card-footer class="justify-between">
                <p class="text-sm text-slate-500">Total data: {{ $attendances->total() }}</p>
                {{ $attendances->links() }}
            </x-ui.card-footer>
        </x-ui.card>
    </div>
</div>
@endsection
