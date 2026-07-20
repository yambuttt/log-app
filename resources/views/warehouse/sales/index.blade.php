@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
            Warehouse Delivery
        </p>
        <h2 class="text-2xl font-bold text-slate-900">
            Laporan Penjualan
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Ringkasan omset, modal, profit, dan margin keuntungan dari pesanan gudang Anda.
        </p>
    </div>

    <!-- Aggregates Stats Grid -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 animate-fade-up">
        <!-- Omset (Total Penjualan) -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 text-xl font-bold shadow-sm">
                    💰
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Penjualan</p>
                    <h3 class="text-xl font-black text-slate-950 mt-0.5">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Total Modal -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700 text-xl font-bold shadow-sm">
                    🧾
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Modal</p>
                    <h3 class="text-xl font-black text-slate-950 mt-0.5">Rp {{ number_format($totalModal, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Keuntungan -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-700 text-xl font-bold shadow-sm">
                    📈
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Keuntungan</p>
                    <h3 class="text-xl font-black text-slate-950 mt-0.5">Rp {{ number_format($totalKeuntungan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>

        <!-- Margin Rata-rata -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:shadow-md">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-fuchsia-100 text-fuchsia-700 text-xl font-bold shadow-sm">
                    📊
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Margin Keuntungan</p>
                    <h3 class="text-xl font-black text-slate-950 mt-0.5">{{ number_format($marginRataRata, 2, ',', '.') }}%</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section (Tanpa Pilihan Gudang) -->
    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <form action="{{ route('warehouse.sales.index') }}" method="GET" class="grid gap-4 md:grid-cols-3 items-end">
            <div>
                <label for="start_date" class="mb-2 block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Mulai</label>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="{{ $startDate }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                >
            </div>

            <div>
                <label for="end_date" class="mb-2 block text-xs font-bold text-slate-600 uppercase tracking-wider">Tanggal Selesai</label>
                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="{{ $endDate }}"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                >
            </div>

            <div class="flex gap-2">
                <button
                    type="submit"
                    class="flex-1 inline-flex items-center justify-center rounded-2xl bg-emerald-900 px-5 py-3.5 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-800"
                >
                    Filter
                </button>
                <a
                    href="{{ route('warehouse.sales.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Orders List / Sales Table -->
    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">No. Order</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Customer</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Total Modal</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Total Jual</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Keuntungan</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Margin</th>
                    </tr>
                </thead>
                @forelse ($orders as $order)
                    <tbody x-data="{ open: false }">
                        <tr 
                            @click="open = !open"
                            class="rounded-2xl bg-white shadow-sm cursor-pointer transition hover:bg-slate-50"
                        >
                            <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900 flex items-center gap-2">
                                <span class="text-slate-400 transition" :class="open ? 'rotate-90' : ''">▶</span>
                                {{ $order->order_number }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600 font-semibold">
                                {{ $order->customer_name }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right text-slate-700 font-medium">
                                Rp {{ number_format($order->total_modal, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right text-slate-950 font-bold">
                                Rp {{ number_format($order->total_penjualan, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-right text-emerald-600 font-bold">
                                Rp {{ number_format($order->keuntungan, 0, ',', '.') }}
                            </td>
                            <td class="rounded-r-2xl px-4 py-4 text-sm text-right">
                                <span class="inline-block rounded-lg px-2.5 py-1 text-xs font-bold 
                                    {{ $order->margin >= 20 ? 'bg-emerald-100 text-emerald-700' : ($order->margin > 0 ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-600') }}">
                                    {{ number_format($order->margin, 2, ',', '.') }}%
                                </span>
                            </td>
                        </tr>

                        <!-- Expandable Details Row -->
                        <tr x-show="open" x-cloak class="bg-slate-50/70">
                            <td colspan="7" class="px-6 py-4 rounded-2xl border border-slate-200/60 shadow-inner">
                                <h4 class="text-sm font-bold text-slate-800 mb-3 flex items-center gap-2">
                                    <span>📦</span> Detail Item Pesanan
                                </h4>
                                <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-sm">
                                    <table class="min-w-full text-sm">
                                        <thead class="bg-slate-50 border-b border-slate-200">
                                            <tr>
                                                <th class="px-4 py-3 text-left font-semibold text-slate-600">Nama Barang</th>
                                                <th class="px-4 py-3 text-center font-semibold text-slate-600">Qty</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-600">Harga Modal</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-600">Harga Jual</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-600">Subtotal Modal</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-600">Subtotal Jual</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-600">Keuntungan</th>
                                                <th class="px-4 py-3 text-right font-semibold text-slate-600">Margin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @foreach ($order->items as $item)
                                                @php
                                                    $itemQty = (float) $item->qty;
                                                    $itemModal = (float) ($item->product->harga_modal ?? 0);
                                                    $itemJual = (float) ($item->product->harga_jual ?? 0);
                                                    $subtotalModal = $itemQty * $itemModal;
                                                    $subtotalJual = $itemQty * $itemJual;
                                                    $itemKeuntungan = $subtotalJual - $subtotalModal;
                                                    $itemMargin = $subtotalJual > 0 ? ($itemKeuntungan / $subtotalJual) * 100 : 0;
                                                @endphp
                                                <tr class="hover:bg-slate-50/50">
                                                    <td class="px-4 py-3 text-slate-800 font-medium">
                                                        {{ $item->product->name ?? '-' }}
                                                        <span class="block text-xs text-slate-400 mt-0.5">SKU: {{ $item->product->sku ?? '-' }}</span>
                                                    </td>
                                                    <td class="px-4 py-3 text-center text-slate-700 font-semibold">
                                                        {{ (float) $item->qty }} {{ $item->product->unit->symbol ?? '' }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right text-slate-600">
                                                        Rp {{ number_format($itemModal, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right text-slate-600 font-medium">
                                                        Rp {{ number_format($itemJual, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right text-slate-600">
                                                        Rp {{ number_format($subtotalModal, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right text-slate-900 font-semibold">
                                                        Rp {{ number_format($subtotalJual, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right text-emerald-600 font-semibold">
                                                        Rp {{ number_format($itemKeuntungan, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        <span class="text-xs font-bold {{ $itemMargin >= 20 ? 'text-emerald-600' : ($itemMargin > 0 ? 'text-blue-600' : 'text-slate-500') }}">
                                                            {{ number_format($itemMargin, 2, ',', '.') }}%
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                @empty
                    <tbody>
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-sm text-slate-500 bg-white rounded-2xl shadow-sm border border-slate-200">
                                Belum ada data penjualan pada periode dan filter terpilih.
                            </td>
                        </tr>
                    </tbody>
                @endforelse
            </table>
        </div>
    </div>
</div>
@endsection
