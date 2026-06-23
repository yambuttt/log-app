@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                Inventory
            </p>
            <h2 class="text-2xl font-bold text-slate-900">
                Stok Barang
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Ringkasan stok barang per gudang dan produk.
            </p>
        </div>
    </div>

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Gudang</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Barang</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">SKU</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Satuan</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Qty</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Update Terakhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stocks as $stock)
                        <tr class="rounded-2xl bg-white shadow-sm">
                            <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900">
                                {{ $stock->warehouse->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-slate-800">
                                {{ $stock->product->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ $stock->product->sku ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ $stock->product->unit->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                                    {{ rtrim(rtrim(number_format($stock->qty, 2, '.', ''), '0'), '.') }}
                                </span>
                            </td>
                            <td class="rounded-r-2xl px-4 py-4 text-sm text-slate-500">
                                {{ $stock->updated_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada data stok. Nanti data akan muncul setelah transaksi barang masuk dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $stocks->links() }}
        </div>
    </div>
</div>
@endsection