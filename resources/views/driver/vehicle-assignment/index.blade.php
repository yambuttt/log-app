@extends('layouts.driver')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
            Delivery
        </p>
        <h2 class="text-2xl font-bold text-slate-900">
            Kendaraan Hari Ini
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Pilih kendaraan yang akan digunakan untuk operasional hari ini.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
            <h3 class="text-lg font-bold text-slate-900">Pilih Kendaraan</h3>
            <p class="mt-1 text-sm text-slate-500">Assignment kendaraan untuk tanggal {{ now()->format('d M Y') }}.</p>

            <form action="{{ route('driver.vehicle-assignment.store') }}" method="POST" class="mt-6 space-y-6">
                @csrf

                <div>
                    <label for="vehicle_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kendaraan
                    </label>
                    <select
                        id="vehicle_id"
                        name="vehicle_id"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-amber-900 focus:ring-4 focus:ring-amber-100"
                    >
                        <option value="">Pilih kendaraan</option>
                        @foreach ($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}" @selected(old('vehicle_id', $todayAssignment->vehicle_id ?? '') == $vehicle->id)>
                                {{ $vehicle->name }} - {{ $vehicle->plate_number }} ({{ $vehicle->vehicle_type === 'small' ? 'Kecil' : 'Besar' }})
                            </option>
                        @endforeach
                    </select>
                    @error('vehicle_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="notes" class="mb-2 block text-sm font-semibold text-slate-700">
                        Catatan
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-amber-900 focus:ring-4 focus:ring-amber-100"
                        placeholder="Catatan tambahan"
                    >{{ old('notes', $todayAssignment->notes ?? '') }}</textarea>
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-amber-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-900/20 transition hover:-translate-y-0.5 hover:bg-amber-800"
                >
                    Simpan Kendaraan Hari Ini
                </button>
            </form>
        </div>

        <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
            <h3 class="text-lg font-bold text-slate-900">Assignment Aktif</h3>
            <p class="mt-1 text-sm text-slate-500">Ringkasan kendaraan yang sedang dipakai hari ini.</p>

            @if ($todayAssignment && $todayAssignment->vehicle)
                <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-sm text-slate-500">Nama Kendaraan</p>
                    <h4 class="text-xl font-bold text-slate-900">{{ $todayAssignment->vehicle->name }}</h4>

                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div>
                            <p class="text-sm text-slate-500">Plat Nomor</p>
                            <p class="font-semibold text-slate-800">{{ $todayAssignment->vehicle->plate_number }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-slate-500">Jenis Kendaraan</p>
                            <p class="font-semibold text-slate-800">
                                {{ $todayAssignment->vehicle->vehicle_type === 'small' ? 'Kecil' : 'Besar' }}
                            </p>
                        </div>
                    </div>

                    @if ($todayAssignment->notes)
                        <div class="mt-4">
                            <p class="text-sm text-slate-500">Catatan</p>
                            <p class="text-sm text-slate-700">{{ $todayAssignment->notes }}</p>
                        </div>
                    @endif
                </div>
            @else
                <div class="mt-6 rounded-2xl border border-dashed border-slate-300 bg-white/60 p-6 text-sm text-slate-500">
                    Belum ada kendaraan yang dipilih untuk hari ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection