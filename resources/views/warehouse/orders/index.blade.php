@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                Warehouse Delivery
            </p>
            <h2 class="text-2xl font-bold text-slate-900">
                Pesanan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola pesanan customer untuk gudang Anda.
            </p>
        </div>

        <a
            href="{{ route('warehouse.orders.create') }}"
            class="inline-flex items-center justify-center rounded-2xl bg-emerald-900 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-800"
        >
            + Tambah Pesanan
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
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">No Pesanan</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Tanggal</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Customer</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Alamat</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Item</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr class="rounded-2xl bg-white shadow-sm align-top">
                            <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900">
                                {{ $order->order_number }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-700">
                                <div class="font-semibold text-slate-900">{{ $order->customer_name }}</div>
                                <div>{{ $order->customer_phone ?: '-' }}</div>
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ $order->delivery_address }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                <div class="space-y-1">
                                    @foreach ($order->items as $item)
                                        <div>
                                            {{ $item->product->name ?? '-' }}
                                            <span class="font-semibold">({{ rtrim(rtrim(number_format($item->qty, 2, '.', ''), '0'), '.') }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="rounded-r-2xl px-4 py-4 text-sm">
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada pesanan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection