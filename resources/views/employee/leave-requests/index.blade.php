@extends('layouts.app')

@section('title', 'Pengajuan Izin / Cuti')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Pengajuan Izin / Cuti</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola dan lacak status pengajuan Anda.</p>
            </div>
            <x-ui.button as="a" href="{{ route('employee.leave-requests.create') }}">
                Ajukan Pengajuan Baru
            </x-ui.button>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        <x-ui.card>
            <x-ui.card-content class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    Periode
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    Jenis
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    Lama
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($leaveRequests as $request)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $request->start_date->translatedFormat('d F Y') }} -
                                        {{ $request->end_date->translatedFormat('d F Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm capitalize text-slate-700">
                                        {{ str_replace('_', ' ', $request->type) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $request->days_count }} hari
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @php
                                            $badgeVariant = match($request->status) {
                                                'approved' => 'success',
                                                'approved_manager' => 'default',
                                                'rejected' => 'danger',
                                                default => 'warning',
                                            };
                                            $label = match($request->status) {
                                                'approved' => 'Disetujui',
                                                'approved_manager' => 'Menunggu Admin',
                                                'rejected' => 'Ditolak',
                                                default => 'Menunggu',
                                            };
                                        @endphp
                                        <x-ui.badge :variant="$badgeVariant">{{ $label }}</x-ui.badge>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <x-ui.button as="a" href="{{ route('employee.leave-requests.show', $request) }}" size="sm" variant="ghost">
                                            Detail
                                        </x-ui.button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Belum ada pengajuan izin atau cuti.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card-content>
            <x-ui.card-footer class="justify-between">
                <p class="text-sm text-slate-500">Total pengajuan: {{ $leaveRequests->total() }}</p>
                <div>
                    {{ $leaveRequests->links() }}
                </div>
            </x-ui.card-footer>
        </x-ui.card>
    </div>
</div>
@endsection
