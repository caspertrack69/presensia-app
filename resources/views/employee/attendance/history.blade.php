@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900 dark:text-slate-100">Riwayat Absensi</h1>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Lihat catatan kehadiran Anda secara lengkap.
            </p>
        </div>
        <x-ui.button as="a" href="{{ route('employee.dashboard') }}" variant="outline" class="w-full md:w-auto">
            Kembali ke Dashboard
        </x-ui.button>
    </div>

    <x-ui.card>
        <x-ui.card-content class="p-0">
            <div class="max-w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-slate-800">
                    <thead class="bg-slate-50 dark:bg-slate-900/40">
                        <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">
                            <th class="px-6 py-3">Tanggal</th>
                            <th class="px-6 py-3">Check In</th>
                            <th class="px-6 py-3">Check Out</th>
                            <th class="px-6 py-3">Status</th>
                            <th class="px-6 py-3">Catatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-900/20">
                        @forelse ($attendances as $attendance)
                            <tr class="text-slate-700 dark:text-slate-200">
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $attendance->date->translatedFormat('d F Y') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $attendance->check_in ? $attendance->check_in->format('H:i') : '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    {{ $attendance->check_out ? $attendance->check_out->format('H:i') : '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4">
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
                                    <x-ui.badge :variant="$badgeVariant">{{ $label }}</x-ui.badge>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-400">
                                    {{ $attendance->notes ?: '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">
                                    Belum ada riwayat absensi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-ui.card-content>

        <x-ui.card-footer class="flex-col gap-4 text-sm text-slate-500 dark:text-slate-400 sm:flex-row sm:items-center sm:justify-between">
            <p>
                Total catatan: {{ $attendances->total() }}
            </p>
            <div class="w-full sm:w-auto">
                {{ $attendances->links() }}
            </div>
        </x-ui.card-footer>
    </x-ui.card>
</div>
@endsection
