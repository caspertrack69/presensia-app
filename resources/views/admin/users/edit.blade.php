@extends('layouts.app')

@section('title', 'Edit Pengguna')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Edit Pengguna</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui data akun {{ $user->name }}.</p>
            </div>
            <x-ui.button as="a" href="{{ route('admin.users.index') }}" variant="outline">
                Kembali
            </x-ui.button>
        </div>

        <x-ui.card>
            <x-ui.card-content>
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium text-slate-700">Nama Lengkap</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                        @error('name')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="text-sm font-medium text-slate-700">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                        @error('email')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="role" class="text-sm font-medium text-slate-700">Role</label>
                            <select id="role" name="role" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                                <option value="employee" @selected(old('role', $user->role) === 'employee')>Employee</option>
                                <option value="manager" @selected(old('role', $user->role) === 'manager')>Manager</option>
                                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                            </select>
                            @error('role')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="department_id" class="text-sm font-medium text-slate-700">Departemen</label>
                            <select id="department_id" name="department_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                                <option value="">-- Pilih Departemen --</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" @selected(old('department_id', $user->department_id) == $department->id)>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="employee_id" class="text-sm font-medium text-slate-700">ID Karyawan</label>
                            <input id="employee_id" type="text" name="employee_id" value="{{ old('employee_id', $user->employee_id) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('employee_id')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="phone" class="text-sm font-medium text-slate-700">Nomor Telepon</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('phone')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="password" class="text-sm font-medium text-slate-700">Password</label>
                            <input id="password" type="password" name="password" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200" placeholder="Kosongkan jika tidak diubah">
                            @error('password')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="password_confirmation" class="text-sm font-medium text-slate-700">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Status Akun</label>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="is_active" value="1" class="h-4 w-4 border-slate-300 text-primary-600 focus:ring-primary-500" @checked(old('is_active', (string) (int) $user->is_active) === '1')>
                                <span class="text-sm text-slate-700">Aktif</span>
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="is_active" value="0" class="h-4 w-4 border-slate-300 text-primary-600 focus:ring-primary-500" @checked(old('is_active', (string) (int) $user->is_active) === '0')>
                                <span class="text-sm text-slate-700">Nonaktif</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <x-ui.button as="a" href="{{ route('admin.users.index') }}" variant="ghost">
                            Batal
                        </x-ui.button>
                        <x-ui.button type="submit">
                            Simpan Perubahan
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card-content>
        </x-ui.card>
    </div>
</div>
@endsection
