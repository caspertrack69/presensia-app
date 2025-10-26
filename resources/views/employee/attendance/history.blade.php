@extends('layouts.app')

@section('title', 'Riwayat Absensi')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Riwayat Absensi</h1>
                <p class="mt-1 text-sm text-slate-500">Lihat catatan kehadiran Anda secara lengkap.</p>
            </div>
            <x-ui-button as="a" href="{{ route('employee.dashboard') }}" variant="outline">
                Kembali ke Dashboard
            </x-ui-button>
        </div>

        <x-ui-card>
            <x-ui-card-content class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check In</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Check Out</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($attendances as $attendance)
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
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $attendance->notes ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500">
                                        Belum ada riwayat absensi.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui-card-content>

            <x-ui-card-footer class="justify-between">
                <p class="text-sm text-slate-500">
                    Total catatan: {{ $attendances->total() }}
                </p>
                <div>
                    {{ $attendances->links() }}
                </div>
            </x-ui-card-footer>
        </x-ui-card>
    </div>
</div>
@endsection
