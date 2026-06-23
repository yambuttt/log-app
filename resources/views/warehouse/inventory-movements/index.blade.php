@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
            Warehouse Inventory
        </p>
        <h2 class="text-2xl font-bold text-slate-900">
            Inventory Movement
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Riwayat pergerakan stok untuk gudang Anda.
        </p>
    </div>

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Barang</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Tipe</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Qty In</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Qty Out</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Stok Sebelum</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Stok Sesudah</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">User</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventoryMovements as $movement)
                        <tr class="rounded-2xl bg-white shadow-sm">
                            <td class="rounded-l-2xl px-4 py-4 text-sm text-slate-700">
                                {{ \Carbon\Carbon::parse($movement->transaction_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                {{ $movement->product->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm">
                                @if ($movement->movement_type === 'goods_in')
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Barang Masuk</span>
                                @elseif ($movement->movement_type === 'waste')
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Waste</span>
                                @elseif ($movement->movement_type === 'opname_plus')
                                    <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Opname Plus</span>
                                @elseif ($movement->movement_type === 'opname_minus')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Opname Minus</span>
                                @else
                                    <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">{{ $movement->movement_type }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                    {{ rtrim(rtrim(number_format($movement->qty_in, 2, '.', ''), '0'), '.') }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm">
                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    {{ rtrim(rtrim(number_format($movement->qty_out, 2, '.', ''), '0'), '.') }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ rtrim(rtrim(number_format($movement->stock_before, 2, '.', ''), '0'), '.') }}
                            </td>
                            <td class="px-4 py-4 text-sm font-semibold text-slate-800">
                                {{ rtrim(rtrim(number_format($movement->stock_after, 2, '.', ''), '0'), '.') }}
                            </td>
                            <td class="rounded-r-2xl px-4 py-4 text-sm text-slate-500">
                                {{ $movement->creator->name ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada inventory movement untuk gudang ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $inventoryMovements->links() }}
        </div>
    </div>
</div>
@endsection