@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                Inventory
            </p>
            <h2 class="text-2xl font-bold text-slate-900">
                Waste
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Catatan pengurangan stok karena rusak, bocor, atau alasan lainnya.
            </p>
        </div>

        <a
            href="{{ route('admin.wastes.create') }}"
            class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800"
        >
            + Tambah Waste
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
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">No Transaksi</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Gudang</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Barang</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Qty</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Alasan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($wastes as $waste)
                        <tr class="rounded-2xl bg-white shadow-sm">
                            <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900">
                                {{ $waste->waste_number }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ \Carbon\Carbon::parse($waste->waste_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ $waste->warehouse->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-slate-800">
                                {{ $waste->product->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    {{ rtrim(rtrim(number_format($waste->qty, 2, '.', ''), '0'), '.') }}
                                </span>
                            </td>
                            <td class="rounded-r-2xl px-4 py-4 text-sm text-slate-600">
                                {{ $waste->reason }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada transaksi waste.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $wastes->links() }}
        </div>
    </div>
</div>
@endsection