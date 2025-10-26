@extends('layouts.app')

@section('title', 'Persetujuan Izin / Cuti')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Persetujuan Izin / Cuti</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola pengajuan izin karyawan pada level administrator.</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <select name="status" class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                    <option value="pending" @selected($status === 'pending')>Menunggu</option>
                    <option value="approved" @selected($status === 'approved')>Disetujui</option>
                    <option value="rejected" @selected($status === 'rejected')>Ditolak</option>
                </select>
                <x-ui.button type="submit" variant="secondary">Terapkan</x-ui.button>
            </form>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ session('error') }}
            </div>
        @endif

        <x-ui.card>
            <x-ui.card-content class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Karyawan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Periode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Jenis</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Catatan Manajer</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($leaveRequests as $request)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        <div class="font-medium text-slate-900">{{ $request->user?->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $request->user?->department?->name }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $request->start_date->format('d M Y') }} - {{ $request->end_date->format('d M Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm capitalize text-slate-700">
                                        {{ str_replace('_', ' ', $request->type) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @php
                                            $variant = match($request->status) {
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'approved_manager' => 'default',
                                                default => 'warning',
                                            };
                                            $label = match($request->status) {
                                                'approved' => 'Disetujui',
                                                'rejected' => 'Ditolak',
                                                'approved_manager' => 'Menunggu Admin',
                                                default => 'Menunggu',
                                            };
                                        @endphp
                                        <x-ui.badge :variant="$variant">{{ $label }}</x-ui.badge>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $request->manager_notes ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        @if (in_array($request->status, ['pending', 'approved_manager'], true))
                                            <div class="flex justify-end gap-2">
                                                <form method="POST" action="{{ route('admin.leave-requests.approve', $request) }}">
                                                    @csrf
                                                    <x-ui.button type="submit" size="sm">Setujui</x-ui.button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.leave-requests.reject', $request) }}" class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="text" name="notes" placeholder="Catatan" class="w-40 rounded-lg border border-slate-300 px-3 py-1 text-xs focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200" required>
                                                    <x-ui.button type="submit" size="sm" variant="destructive">Tolak</x-ui.button>
                                                </form>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-500">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Tidak ada pengajuan pada status ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card-content>
            <x-ui.card-footer class="justify-between">
                <p class="text-sm text-slate-500">Total data: {{ $leaveRequests->total() }}</p>
                {{ $leaveRequests->links() }}
            </x-ui.card-footer>
        </x-ui.card>
    </div>
</div>
@endsection
