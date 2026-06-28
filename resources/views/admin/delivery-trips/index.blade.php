@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Fleet & Delivery</p>
            <h2 class="text-2xl font-bold text-slate-900">Delivery Trips</h2>
            <p class="mt-1 text-sm text-slate-500">Monitoring seluruh trip pengiriman barang dan konsumsi bahan bakar kendaraan.</p>
        </div>
    </div>

    <div class="space-y-6">
        @forelse ($trips as $trip)
            <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between border-b border-slate-100 pb-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-500">{{ $trip->trip_number }}</span>
                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">
                                🏠 {{ $trip->warehouse->name ?? '-' }}
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900">
                            {{ $trip->driver->name ?? '-' }}
                        </h3>
                        <p class="text-sm text-slate-600">
                            Kendaraan: <span class="font-semibold text-slate-800">{{ $trip->vehicle->name ?? '-' }} ({{ $trip->vehicle->plate_number ?? '-' }})</span>
                            • Konsumsi Bensin: <span class="font-semibold text-slate-800">{{ $trip->vehicle->fuel_efficiency ?? '10.00' }} km/l</span>
                        </p>
                        <p class="text-xs text-slate-500">
                            Tanggal: {{ \Carbon\Carbon::parse($trip->trip_date)->format('d M Y') }}
                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        @if ($trip->status === 'planned')
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Planned</span>
                        @elseif ($trip->status === 'on_trip')
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">On Trip</span>
                        @else
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">{{ ucfirst($trip->status) }}</span>
                        @endif

                        <div class="mt-2 text-right">
                            <span class="text-xs font-medium text-slate-500 block">Estimasi Konsumsi Bensin</span>
                            <span class="text-lg font-bold text-emerald-700">
                                ⛽ {{ $trip->fuel_consumed_liters }} Liter
                            </span>
                        </div>

                        <a href="{{ route('delivery-trips.print-all-surat-jalan', $trip->id) }}" target="_blank"
                           class="mt-2 inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-slate-800 transition">
                            🖨️ Print Semua Surat Jalan
                        </a>
                    </div>
                </div>

                <div class="mt-4 grid gap-4 md:grid-cols-3 bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                    <div class="space-y-1">
                        <span class="text-xs text-slate-500 block">Jarak Rute Pengiriman</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $trip->total_estimated_distance_km }} km</span>
                    </div>
                    <div class="space-y-1 border-slate-200 md:border-l md:pl-4">
                        <span class="text-xs text-slate-500 block">Jarak Pulang (Last Stop ➔ Gudang)</span>
                        <span class="text-sm font-semibold text-slate-800">{{ $trip->return_distance_km }} km</span>
                    </div>
                    <div class="space-y-1 border-slate-200 md:border-l md:pl-4">
                        <span class="text-xs text-slate-500 block">Total Jarak Tempuh Trip</span>
                        <span class="text-sm font-bold text-slate-900">{{ $trip->total_trip_distance_km }} km</span>
                    </div>
                </div>

                <div class="mt-5 space-y-2">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Rute Stop Pengiriman</p>
                    @foreach ($trip->shipments as $shipment)
                        <div class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-700">
                            <div>
                                <span class="font-semibold text-slate-900">Stop {{ $shipment->pivot->route_order }}</span>
                                <span class="mx-2 text-slate-300">|</span>
                                {{ $shipment->order->customer_name ?? '-' }}
                                <span class="text-slate-400 text-xs ml-2">({{ $shipment->order->delivery_address ?? '-' }})</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-semibold text-slate-500">
                                    {{ $shipment->pivot->route_order === 1 ? 'dari gudang' : 'dari titik sebelumnya' }}: {{ $shipment->pivot->distance_from_previous_km }} km
                                </span>
                                <a href="{{ route('shipments.print-surat-jalan', $shipment->id) }}" target="_blank"
                                   class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                                    🖨️ Print Surat Jalan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="glass-panel rounded-[24px] border border-white/50 p-8 text-center text-sm text-slate-500 shadow-lg shadow-slate-200/50">
                Belum ada trip aktif yang terdaftar.
            </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $trips->links() }}
    </div>
</div>
@endsection
