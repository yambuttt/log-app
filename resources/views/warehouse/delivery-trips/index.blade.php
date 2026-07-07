@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Warehouse Delivery</p>
            <h2 class="text-2xl font-bold text-slate-900">Delivery Trips</h2>
            <p class="mt-1 text-sm text-slate-500">1 trip = 1 driver = 1 kendaraan = banyak shipment.</p>
        </div>

        <a href="{{ route('warehouse.delivery-trips.create') }}"
           class="inline-flex items-center justify-center rounded-2xl bg-emerald-900 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-800">
            + Buat Trip
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="space-y-4">
        @forelse ($trips as $trip)
            <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">{{ $trip->trip_number }}</p>
                        <h3 class="text-xl font-bold text-slate-900">{{ $trip->driver->name ?? '-' }}</h3>
                        <p class="text-sm text-slate-600">
                            {{ $trip->vehicle->name ?? '-' }} ({{ $trip->vehicle->plate_number ?? '-' }})
                            • Konsumsi Bensin: {{ $trip->vehicle->fuel_efficiency ?? '10.00' }} km/l
                        </p>
                        <p class="mt-2 text-sm text-slate-500">
                            {{ \Carbon\Carbon::parse($trip->trip_date)->format('d M Y') }} •
                            {{ $trip->total_shipments }} shipment •
                            Total Jarak: <span class="font-bold text-slate-800">{{ $trip->total_trip_distance_km }} km</span>
                            (Rute: {{ $trip->total_estimated_distance_km }} km + Pulang: {{ $trip->return_distance_km }} km)
                            • <span class="font-bold text-emerald-700">⛽ {{ $trip->fuel_consumed_liters }} Liter</span>
                        </p>
                    </div>

                    <div class="flex flex-col items-end gap-2">
                        @if ($trip->status === 'planned')
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Planned</span>
                        @elseif ($trip->status === 'on_trip')
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">🚚 On Trip</span>
                        @elseif ($trip->status === 'returning')
                            <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">🏠 Return Home</span>
                        @elseif ($trip->status === 'completed')
                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">✅ Completed</span>
                        @else
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ ucfirst($trip->status) }}</span>
                        @endif
                        <a href="{{ route('delivery-trips.print-all-surat-jalan', $trip->id) }}" target="_blank"
                           class="mt-2 inline-flex items-center gap-1.5 rounded-xl bg-emerald-900 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-800 transition">
                            🖨️ Print Semua Surat Jalan
                        </a>
                    </div>
                </div>

                <div class="mt-5 space-y-2">
                    @foreach ($trip->shipments as $shipment)
                        <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700">
                            <div>
                                <span class="font-semibold">Stop {{ $shipment->pivot->route_order }}</span>
                                — {{ $shipment->order->customer_name ?? '-' }}
                                <span class="text-slate-500">
                                    ({{ $shipment->pivot->route_order === 1 ? 'dari gudang' : 'dari titik sebelumnya' }}: {{ $shipment->pivot->distance_from_previous_km }} km)
                                </span>
                            </div>
                            <a href="{{ route('shipments.print-surat-jalan', $shipment->id) }}" target="_blank"
                               class="inline-flex items-center gap-1 rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100 transition">
                                🖨️ Print Surat Jalan
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="glass-panel rounded-[24px] border border-white/50 p-8 text-center text-sm text-slate-500 shadow-lg">
                Belum ada trip.
            </div>
        @endforelse
    </div>

    <div>
        {{ $trips->links() }}
    </div>
</div>
@endsection