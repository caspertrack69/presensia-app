@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-7xl mx-auto space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Manajemen Pengguna</h1>
                <p class="mt-1 text-sm text-slate-500">Kelola akun karyawan, manajer, dan admin.</p>
            </div>
            <x-ui.button as="a" href="{{ route('admin.users.create') }}">
                Tambah Pengguna
            </x-ui.button>
        </div>

        <x-ui.card>
            <x-ui.card-content>
                <form method="GET" class="grid grid-cols-1 gap-4 md:grid-cols-4 md:items-end">
                    <div class="md:col-span-2 space-y-2">
                        <label for="search" class="text-sm font-medium text-slate-700">Pencarian</label>
                        <input id="search" type="text" name="search" value="{{ $search }}" placeholder="Cari nama, email, atau ID karyawan" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                    </div>
                    <div class="space-y-2">
                        <label for="role" class="text-sm font-medium text-slate-700">Role</label>
                        <select id="role" name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            <option value="">Semua Role</option>
                            <option value="employee" @selected($role === 'employee')>Employee</option>
                            <option value="manager" @selected($role === 'manager')>Manager</option>
                            <option value="admin" @selected($role === 'admin')>Admin</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <x-ui.button type="submit" class="flex-1">Cari</x-ui.button>
                        <x-ui.button as="a" href="{{ route('admin.users.index') }}" variant="outline" class="flex-1">
                            Reset
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
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Departemen</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($users as $user)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        <div class="font-medium text-slate-900">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-500">ID: {{ $user->employee_id ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">{{ $user->email }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm capitalize text-slate-700">{{ $user->role }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                        {{ $user->department?->name ?? 'Belum diatur' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm">
                                        @if ($user->is_active)
                                            <x-ui.badge variant="success">Aktif</x-ui.badge>
                                        @else
                                            <x-ui.badge variant="muted">Nonaktif</x-ui.badge>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div class="flex justify-end gap-2">
                                            <x-ui.button as="a" href="{{ route('admin.users.edit', $user) }}" size="sm" variant="ghost">
                                                Ubah
                                            </x-ui.button>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <x-ui.button type="submit" size="sm" variant="destructive">Hapus</x-ui.button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-slate-500">
                                        Tidak ditemukan pengguna dengan filter tersebut.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-ui.card-content>
            <x-ui.card-footer class="justify-between">
                <p class="text-sm text-slate-500">Total pengguna: {{ $users->total() }}</p>
                {{ $users->links() }}
            </x-ui.card-footer>
        </x-ui.card>
    </div>
</div>
@endsection
