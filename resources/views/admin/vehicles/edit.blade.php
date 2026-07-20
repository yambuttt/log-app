@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
            Fleet
        </p>
        <h2 class="text-2xl font-bold text-slate-900">
            Edit Kendaraan
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Perbarui data kendaraan operasional dan status perawatannya.
        </p>
    </div>

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <form action="{{ route('admin.vehicles.update', $vehicle) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @include('admin.vehicles._form')

            <!-- Section Maintenance -->
            <div x-data="{ inMaintenance: {{ old('in_maintenance', $vehicle->status === 'maintenance') ? 'true' : 'false' }} }" class="border-t border-slate-100 pt-6 space-y-4">
                <div class="flex items-center">
                    <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700 cursor-pointer">
                        <input
                            type="checkbox"
                            name="in_maintenance"
                            value="1"
                            x-model="inMaintenance"
                            class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                        >
                        <span>Kendaraan sedang dalam perawatan (Maintenance)</span>
                    </label>
                </div>

                <div x-show="inMaintenance" x-cloak x-transition class="space-y-2">
                    <label for="maintenance_notes" class="block text-sm font-semibold text-slate-700">Catatan Kerusakan / Perbaikan</label>
                    <textarea
                        id="maintenance_notes"
                        name="maintenance_notes"
                        rows="3"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        placeholder="Contoh: Ganti oli berkala, perbaikan transmisi, ban bocor, dll."
                    >{{ old('maintenance_notes', $vehicle->maintenance_notes) }}</textarea>
                    @error('maintenance_notes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-4 sm:flex-row">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800"
                >
                    Perbarui Kendaraan
                </button>

                <a
                    href="{{ route('admin.vehicles.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>

    <!-- Maintenance Logs Table -->
    @if ($vehicle->maintenanceLogs && $vehicle->maintenanceLogs->isNotEmpty())
        <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50 animate-fade-up">
            <div class="mb-4">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span>🛠️</span> Log Perbaikan Kendaraan
                </h3>
                <p class="text-sm text-slate-500">Daftar riwayat perbaikan dan masa perawatan kendaraan ini.</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full border-separate border-spacing-y-2">
                    <thead>
                        <tr class="text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                            <th class="px-4 py-2">Mulai Perbaikan</th>
                            <th class="px-4 py-2">Selesai Perbaikan</th>
                            <th class="px-4 py-2">Catatan Kerusakan / Perbaikan</th>
                            <th class="px-4 py-2">Durasi Perawatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicle->maintenanceLogs as $log)
                            <tr class="bg-slate-50/50 rounded-xl">
                                <td class="px-4 py-3 text-sm text-slate-700 rounded-l-xl font-medium">
                                    {{ $log->start_date->format('d M Y H:i') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-700">
                                    @if ($log->end_date)
                                        {{ $log->end_date->format('d M Y H:i') }}
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-bold text-amber-800">
                                            Sedang Diperbaiki
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600">
                                    {{ $log->notes ?: '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-500 rounded-r-xl">
                                    @if ($log->end_date)
                                        {{ $log->start_date->diffForHumans($log->end_date, true) }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
