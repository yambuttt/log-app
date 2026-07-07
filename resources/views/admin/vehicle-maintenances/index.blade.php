@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                Fleet Management
            </p>
            <h2 class="text-2xl font-bold text-slate-900">
                Maintenance & Perbaikan Kendaraan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola kendaraan yang sedang diperbaiki atau dalam masa perawatan.
            </p>
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Form Masukkan Kendaraan Ke Maintenance -->
        <div class="lg:col-span-1">
            <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Masukkan Kendaraan</h3>
                
                <form action="{{ route('admin.vehicle-maintenances.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Kendaraan</label>
                        <select name="vehicle_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-slate-900 focus:ring-4 focus:ring-slate-100">
                            <option value="">-- Pilih Kendaraan Aktif --</option>
                            @foreach($availableVehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>
                                    {{ $vehicle->name }} ({{ $vehicle->plate_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('vehicle_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan Kerusakan / Perbaikan</label>
                        <textarea name="maintenance_notes" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-slate-900 focus:ring-4 focus:ring-slate-100" placeholder="Contoh: Ganti oli berkala, perbaikan transmisi, ban bocor, dll.">{{ old('maintenance_notes') }}</textarea>
                        @error('maintenance_notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center rounded-2xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:bg-red-700">
                        🛠️ Tandai Sedang Diperbaiki
                    </button>
                </form>

                <div class="mt-4 rounded-xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-800">
                    <strong>PENTING:</strong> Kendaraan yang dimasukkan ke daftar ini akan otomatis dinonaktifkan dari sistem penugasan driver. Jika hari ini kendaraan tersebut sudah di-assign driver, penugasan akan otomatis dicopot.
                </div>
            </div>
        </div>

        <!-- Daftar Kendaraan Sedang Maintenance -->
        <div class="lg:col-span-2">
            <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
                <h3 class="text-lg font-bold text-slate-900 mb-4">Kendaraan Sedang Diperbaiki ({{ $maintenanceVehicles->total() }})</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full border-separate border-spacing-y-3">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Kendaraan</th>
                                <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Nomor Plat</th>
                                <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Detail Perbaikan</th>
                                <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Mulai Perbaikan</th>
                                <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($maintenanceVehicles as $vehicle)
                                <tr class="rounded-2xl bg-white shadow-sm align-top">
                                    <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900">
                                        {{ $vehicle->name }}
                                        <div class="text-xs text-slate-500 mt-0.5">
                                            Jenis: {{ $vehicle->vehicle_type === 'small' ? 'Kecil' : 'Besar' }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-600 font-medium">
                                        {{ $vehicle->plate_number }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-600">
                                        {{ $vehicle->maintenance_notes ?? 'Tidak ada catatan khusus.' }}
                                    </td>
                                    <td class="px-4 py-4 text-sm text-slate-500">
                                        {{ $vehicle->updated_at->format('d M Y H:i') }}
                                    </td>
                                    <td class="rounded-r-2xl px-4 py-4 text-sm">
                                        <form action="{{ route('admin.vehicle-maintenances.release', $vehicle) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center gap-1 rounded-xl bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                                                ✅ Selesai Perbaikan
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-10 text-center text-sm text-slate-500">
                                        Tidak ada kendaraan yang sedang diperbaiki.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $maintenanceVehicles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
