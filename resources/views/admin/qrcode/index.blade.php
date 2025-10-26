@extends('layouts.app')

@section('title', 'Manajemen QR Code')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Manajemen QR Code</h1>
                <p class="mt-1 text-sm text-slate-500">Riwayat dan status QR Code dinamis yang pernah dibuat.</p>
            </div>
            <x-ui.button as="a" href="{{ route('admin.qrcode.display') }}">
                Buka Layar QR Aktif
            </x-ui.button>
        </div>

        <x-ui.card>
            <x-ui.card-content class="p-0">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Lokasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Token</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Dibuat Oleh</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Kadaluarsa</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($qrCodes as $qrCode)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $qrCode->location_name }}</td>
                                    <td class="px-6 py-4 text-sm font-mono text-slate-600">{{ \Illuminate\Support\Str::limit($qrCode->token, 16) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $qrCode->creator?->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ optional($qrCode->expires_at)->translatedFormat('d F Y H:i:s') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @if ($qrCode->isValid())
                                            <x-ui.badge variant="success">Aktif</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="muted">Tidak Aktif</x-ui.badge>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">Belum ada QR Code yang dibuat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card-content>
            <x-ui.card-footer class="justify-between">
                <p class="text-sm text-slate-500">Total QR Code: {{ $qrCodes->total() }}</p>
                {{ $qrCodes->links() }}
            </x-ui.card-footer>
        </x-ui.card>
    </div>
</div>
@endsection
