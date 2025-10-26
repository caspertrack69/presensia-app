@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Detail Pengajuan</h1>
                <p class="mt-1 text-sm text-slate-500">Informasi lengkap terkait pengajuan izin / cuti Anda.</p>
            </div>
            <x-ui.button as="a" href="{{ route('employee.leave-requests.index') }}" variant="outline">
                Kembali
            </x-ui.button>
        </div>

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Status Pengajuan</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                @php
                    $statusVariant = match($leaveRequest->status) {
                        'approved' => 'success',
                        'approved_manager' => 'default',
                        'rejected' => 'danger',
                        default => 'warning',
                    };
                    $statusLabel = match($leaveRequest->status) {
                        'approved' => 'Disetujui HR',
                        'approved_manager' => 'Disetujui Manajer',
                        'rejected' => 'Ditolak',
                        default => 'Menunggu Persetujuan',
                    };
                @endphp

                <x-ui.badge :variant="$statusVariant">{{ $statusLabel }}</x-ui.badge>

                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Periode</dt>
                        <dd class="mt-1 text-sm text-slate-900">
                            {{ $leaveRequest->start_date->translatedFormat('d F Y') }} -
                            {{ $leaveRequest->end_date->translatedFormat('d F Y') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Jumlah Hari</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $leaveRequest->days_count }} hari</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Jenis</dt>
                        <dd class="mt-1 text-sm capitalize text-slate-900">
                            {{ str_replace('_', ' ', $leaveRequest->type) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Diajukan pada</dt>
                        <dd class="mt-1 text-sm text-slate-900">{{ $leaveRequest->created_at->translatedFormat('d F Y H:i') }}</dd>
                    </div>
                </dl>

                <div>
                    <dt class="text-sm font-medium text-slate-500">Alasan</dt>
                    <dd class="mt-2 text-sm text-slate-700 whitespace-pre-line">{{ $leaveRequest->reason }}</dd>
                </div>

                @if ($leaveRequest->attachment)
                    <div>
                        <dt class="text-sm font-medium text-slate-500">Lampiran</dt>
                        <dd class="mt-2">
                            <x-ui.button as="a" href="{{ Storage::url($leaveRequest->attachment) }}" target="_blank" variant="outline" size="sm">
                                Lihat Lampiran
                            </x-ui.button>
                        </dd>
                    </div>
                @endif
            </x-ui.card-content>
        </x-ui.card>

        <x-ui.card>
            <x-ui.card-header>
                <x-ui.card-title>Riwayat Persetujuan</x-ui.card-title>
            </x-ui.card-header>
            <x-ui.card-content class="space-y-4">
                <div class="space-y-2 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-sm font-medium text-slate-700">Persetujuan Manajer</p>
                    <p class="text-sm text-slate-600">
                        Status:
                        @if ($leaveRequest->approved_by_manager)
                            Disetujui oleh {{ optional($leaveRequest->managerApprover)->name }}
                            pada {{ optional($leaveRequest->manager_approved_at)->translatedFormat('d F Y H:i') }}
                        @elseif($leaveRequest->status === 'rejected' && $leaveRequest->manager_approved_at)
                            Ditolak oleh {{ optional($leaveRequest->managerApprover)->name }}
                            pada {{ optional($leaveRequest->manager_approved_at)->translatedFormat('d F Y H:i') }}
                        @else
                            Menunggu tindakan manajer.
                        @endif
                    </p>
                    @if ($leaveRequest->manager_notes)
                        <p class="rounded-md bg-white px-3 py-2 text-sm text-slate-600">
                            Catatan: {{ $leaveRequest->manager_notes }}
                        </p>
                    @endif
                </div>

                <div class="space-y-2 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                    <p class="text-sm font-medium text-slate-700">Persetujuan Admin / HR</p>
                    <p class="text-sm text-slate-600">
                        Status:
                        @if ($leaveRequest->approved_by_admin)
                            Disetujui oleh {{ optional($leaveRequest->adminApprover)->name }}
                            pada {{ optional($leaveRequest->admin_approved_at)->translatedFormat('d F Y H:i') }}
                        @elseif($leaveRequest->status === 'rejected' && $leaveRequest->admin_approved_at)
                            Ditolak oleh {{ optional($leaveRequest->adminApprover)->name }}
                            pada {{ optional($leaveRequest->admin_approved_at)->translatedFormat('d F Y H:i') }}
                        @else
                            Menunggu tindakan admin.
                        @endif
                    </p>
                    @if ($leaveRequest->admin_notes)
                        <p class="rounded-md bg-white px-3 py-2 text-sm text-slate-600">
                            Catatan: {{ $leaveRequest->admin_notes }}
                        </p>
                    @endif
                </div>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection
