@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                Fleet
            </p>
            <h2 class="text-2xl font-bold text-slate-900">
                Kapasitas Kendaraan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Atur kapasitas maksimal kendaraan untuk setiap jenis gas.
            </p>
        </div>

        <a
            href="{{ route('admin.vehicle-capacities.create') }}"
            class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800"
        >
            + Tambah Kapasitas
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Kendaraan</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Plat Nomor</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Produk</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Maksimal Muat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicleCapacities as $capacity)
                        <tr class="rounded-2xl bg-white shadow-sm">
                            <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900">
                                {{ $capacity->vehicle->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ $capacity->vehicle->plate_number ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                {{ $capacity->product->name ?? '-' }}
                            </td>
                            <td class="rounded-r-2xl px-4 py-4 text-sm">
                                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                    {{ $capacity->max_qty }} tabung
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada data kapasitas kendaraan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $vehicleCapacities->links() }}
        </div>
    </div>
</div>
@endsection