@extends('layouts.app')

@section('title', 'Ajukan Izin / Cuti')

@section('content')
<div class="min-h-screen bg-slate-50 py-10 px-4">
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">Ajukan Izin / Cuti</h1>
            <p class="mt-1 text-sm text-slate-500">Lengkapi formulir berikut untuk mengajukan izin atau cuti.</p>
        </div>

        <x-ui-card>
            <x-ui-card-content>
                <form method="POST" action="{{ route('employee.leave-requests.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label for="type" class="text-sm font-medium text-slate-700">Jenis Pengajuan</label>
                            <select id="type" name="type" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                                <option value="annual" @selected(old('type') === 'annual')>Cuti Tahunan</option>
                                <option value="sick" @selected(old('type') === 'sick')>Sakit</option>
                                <option value="unpaid" @selected(old('type') === 'unpaid')>Cuti Tanpa Bayar</option>
                                <option value="other" @selected(old('type') === 'other')>Lainnya</option>
                            </select>
                            @error('type')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="attachment" class="text-sm font-medium text-slate-700">Lampiran (opsional)</label>
                            <input id="attachment" type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            <p class="text-xs text-slate-500">Format yang didukung: PDF, JPG, JPEG, PNG (max. 4MB).</p>
                            @error('attachment')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="space-y-2">
                            <label for="start_date" class="text-sm font-medium text-slate-700">Tanggal Mulai</label>
                            <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('start_date')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="space-y-2">
                            <label for="end_date" class="text-sm font-medium text-slate-700">Tanggal Selesai</label>
                            <input id="end_date" type="date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">
                            @error('end_date')
                                <p class="text-sm text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="reason" class="text-sm font-medium text-slate-700">Alasan Pengajuan</label>
                        <textarea id="reason" name="reason" rows="5" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-200">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <x-ui-button as="a" href="{{ route('employee.leave-requests.index') }}" variant="ghost">
                            Batal
                        </x-ui-button>
                        <x-ui-button type="submit">
                            Kirim Pengajuan
                        </x-ui-button>
                    </div>
                </form>
            </x-ui-card-content>
        </x-ui-card>
    </div>
</div>
@endsection
