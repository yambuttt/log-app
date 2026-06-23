@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Warehouse Inventory</p>
            <h2 class="text-2xl font-bold text-slate-900">Stock Opname</h2>
            <p class="mt-1 text-sm text-slate-500">Catatan stock opname untuk gudang Anda.</p>
        </div>

        <a href="{{ route('warehouse.stock-opnames.create') }}"
           class="inline-flex items-center justify-center rounded-2xl bg-emerald-900 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-800">
            + Tambah Stock Opname
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">No Transaksi</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Barang</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Sistem</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Fisik</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockOpnames as $opname)
                        <tr class="rounded-2xl bg-white shadow-sm">
                            <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900">{{ $opname->opname_number }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ \Carbon\Carbon::parse($opname->opname_date)->format('d M Y') }}</td>
                            <td class="px-4 py-4 text-sm font-semibold text-slate-800">{{ $opname->product->name ?? '-' }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ rtrim(rtrim(number_format($opname->system_qty, 2, '.', ''), '0'), '.') }}</td>
                            <td class="px-4 py-4 text-sm text-slate-600">{{ rtrim(rtrim(number_format($opname->physical_qty, 2, '.', ''), '0'), '.') }}</td>
                            <td class="rounded-r-2xl px-4 py-4 text-sm">
                                @if ($opname->difference_qty > 0)
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        +{{ rtrim(rtrim(number_format($opname->difference_qty, 2, '.', ''), '0'), '.') }}
                                    </span>
                                @elseif ($opname->difference_qty < 0)
                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        {{ rtrim(rtrim(number_format($opname->difference_qty, 2, '.', ''), '0'), '.') }}
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">0</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">Belum ada transaksi stock opname.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $stockOpnames->links() }}</div>
    </div>
</div>
@endsection