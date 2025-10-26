@extends('layouts.app')

@section('title', 'Manajemen Departemen')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Departemen</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola departemen, lokasi kerja, dan kepala divisi.</p>
            </div>
            <x-ui.button as="a" href="{{ route('admin.departments.create') }}">
                Tambah Departemen
            </x-ui.button>
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
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Kepala Divisi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($departments as $department)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-slate-700">
                                        <div class="font-medium text-slate-900">{{ $department->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $department->description ?? 'Tidak ada deskripsi' }}</div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $department->code }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $department->manager?->name ?? 'Belum ditetapkan' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @if ($department->is_active)
                                            <x-ui.badge variant="success">Aktif</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="muted">Nonaktif</x-ui.badge>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div class="flex justify-end gap-2">
                                            <x-ui.button as="a" href="{{ route('admin.departments.edit', $department) }}" size="sm" variant="ghost">
                                                Ubah
                                            </x-ui.button>
                                            <form method="POST" action="{{ route('admin.departments.destroy', $department) }}" onsubmit="return confirm('Hapus departemen ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-ui.button type="submit" size="sm" variant="destructive">
                                                    Hapus
                                                </x-ui.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Belum ada data departemen.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card-content>
            <x-ui.card-footer class="justify-between">
                <p class="text-sm text-slate-500">Total departemen: {{ $departments->total() }}</p>
                {{ $departments->links() }}
            </x-ui.card-footer>
        </x-ui.card>
    </div>
</div>
@endsection
