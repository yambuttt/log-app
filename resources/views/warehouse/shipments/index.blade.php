@extends('layouts.warehouse')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                    Warehouse Delivery
                </p>
                <h2 class="text-2xl font-bold text-slate-900">
                    Pengiriman
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Daftar pengiriman. Driver & kendaraan ditentukan saat membuat Delivery Trip.
                </p>
            </div>

            <a href="{{ route('warehouse.shipments.create') }}"
                class="inline-flex items-center justify-center rounded-2xl bg-emerald-900 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-800">
                + Buat Pengiriman
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
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">No Pengiriman</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Tanggal</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Pesanan</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Estimasi Jarak</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Maps</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Delivery Trip</th>
                            <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shipments as $shipment)
                            @php
                                $deliveryTrip = $shipment->tripShipments->first()?->deliveryTrip ?? null;
                            @endphp
                            <tr class="rounded-2xl bg-white shadow-sm align-top">
                                <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900">
                                    {{ $shipment->shipment_number }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600">
                                    {{ \Carbon\Carbon::parse($shipment->shipment_date)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    <div class="font-semibold text-slate-900">{{ $shipment->order->order_number ?? '-' }}</div>
                                    <div>{{ $shipment->order->customer_name ?? '-' }}</div>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    @if ($shipment->estimated_distance_km || $shipment->estimated_duration_minutes)
                                        <div class="font-semibold text-slate-900">
                                            {{ $shipment->estimated_distance_km ? $shipment->estimated_distance_km . ' km' : '-' }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            {{ $shipment->estimated_duration_minutes ? $shipment->estimated_duration_minutes . ' menit' : '-' }}
                                        </div>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    @if ($shipment->google_maps_url)
                                        <a href="{{ $shipment->google_maps_url }}" target="_blank"
                                            class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 hover:bg-emerald-100 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Buka Maps
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">
                                    @if ($deliveryTrip)
                                        <div class="font-semibold text-slate-900">{{ $deliveryTrip->trip_number }}</div>
                                        <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($deliveryTrip->trip_date)->format('d M Y') }}</div>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                                            Belum ada trip
                                        </span>
                                    @endif
                                </td>
                                <td class="rounded-r-2xl px-4 py-4 text-sm">
                                    @if ($shipment->status === 'pending')
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                            ⏳ Pending
                                        </span>
                                    @elseif ($shipment->status === 'assigned')
                                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                                            ✅ Assigned
                                        </span>
                                    @elseif ($shipment->status === 'on_delivery')
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                            🚚 On Delivery
                                        </span>
                                    @elseif ($shipment->status === 'completed')
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            ✅ Selesai
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ ucfirst($shipment->status) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-500">
                                    Belum ada pengiriman.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $shipments->links() }}
            </div>
        </div>
    </div>
@endsection