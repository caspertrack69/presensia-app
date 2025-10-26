@extends('layouts.app')

@section('title', 'Edit Departemen')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Edit Departemen</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui informasi departemen {{ $department->name }}.</p>
            </div>
            <x-ui-button as="a" href="{{ route('admin.departments.index') }}" variant="outline">
                Kembali
            </x-ui-button>
        </div>

        <x-ui-card>
            <x-ui-card-content>
                <form method="POST" action="{{ route('admin.departments.update', $department) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-2">
                        <label for="name" class="text-sm font-medium text-slate-700">Nama Departemen</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $department->name) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                        @error('name')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="code" class="text-sm font-medium text-slate-700">Kode Departemen</label>
                            <input id="code" type="text" name="code" value="{{ old('code', $department->code) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('code')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="manager_id" class="text-sm font-medium text-slate-700">Kepala Divisi</label>
                            <select id="manager_id" name="manager_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                                <option value="">-- Pilih Kepala Divisi --</option>
                                @foreach ($managers as $manager)
                                    <option value="{{ $manager->id }}" @selected(old('manager_id', $department->manager_id) == $manager->id)>
                                        {{ $manager->name }} ({{ ucfirst($manager->role) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('manager_id')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="description" class="text-sm font-medium text-slate-700">Deskripsi</label>
                        <textarea id="description" name="description" rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">{{ old('description', $department->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-700">Status</label>
                        <div class="flex items-center gap-3">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="is_active" value="1" class="h-4 w-4 border-slate-300 text-primary-600 focus:ring-primary-500" @checked(old('is_active', (string) (int) $department->is_active) === '1')>
                                <span class="text-sm text-slate-700">Aktif</span>
                            </label>
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="is_active" value="0" class="h-4 w-4 border-slate-300 text-primary-600 focus:ring-primary-500" @checked(old('is_active', (string) (int) $department->is_active) === '0')>
                                <span class="text-sm text-slate-700">Nonaktif</span>
                            </label>
                        </div>
                        @error('is_active')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-3">
                        <x-ui-button as="a" href="{{ route('admin.departments.index') }}" variant="ghost">
                            Batal
                        </x-ui-button>
                        <x-ui-button type="submit">
                            Simpan Perubahan
                        </x-ui-button>
                    </div>
                </form>
            </x-ui-card-content>
        </x-ui-card>
    </div>
</div>
@endsection
