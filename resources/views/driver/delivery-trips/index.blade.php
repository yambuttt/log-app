@extends('layouts.driver')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
            Delivery Trip
        </p>
        <h2 class="text-2xl font-bold text-slate-900">
            Trip Pengiriman
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Daftar trip aktif dengan urutan rute Nearest Neighbor.
        </p>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-5">
        @forelse ($trips as $trip)
            @php
                $allStopsCompleted = $trip->tripShipments->every(fn($ts) => $ts->shipment?->status === 'completed');
            @endphp
            <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <div class="text-sm text-slate-500">{{ $trip->trip_number }}</div>
                        <h3 class="text-xl font-bold text-slate-900">
                            {{ $trip->vehicle->name ?? '-' }} - {{ $trip->vehicle->plate_number ?? '-' }}
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            {{ \Carbon\Carbon::parse($trip->trip_date)->format('d M Y') }}
                            • {{ $trip->total_shipments }} stop
                            • {{ $trip->total_estimated_distance_km }} km
                        </p>
                    </div>

                    <div class="space-y-3">
                        @if ($trip->status === 'planned')
                            <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                                Planned
                            </span>

                            <form action="{{ route('driver.delivery-trips.start', $trip) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-2xl bg-amber-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-800">
                                    Mulai Trip
                                </button>
                            </form>

                        @elseif ($trip->status === 'on_trip')
                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                                🚚 On Trip
                            </span>

                            {{-- Tombol Return Home muncul hanya jika semua stop sudah selesai --}}
                            @if ($allStopsCompleted)
                                <form action="{{ route('driver.delivery-trips.return-home', $trip) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-2xl bg-indigo-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-600">
                                        🏠 Return Home
                                    </button>
                                </form>
                            @endif

                        @elseif ($trip->status === 'returning')
                            <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                                🏠 Return Home
                            </span>

                            <form action="{{ route('driver.delivery-trips.finish', $trip) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-2xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600">
                                    ✅ Sampai di Gudang
                                </button>
                            </form>

                        @else
                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                {{ ucfirst($trip->status) }}
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    @foreach ($trip->tripShipments->sortBy('route_order') as $tripShipment)
                        @php $shipment = $tripShipment->shipment; @endphp

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                <div class="space-y-2">
                                    <div class="text-sm font-semibold text-emerald-700">
                                        Stop {{ $tripShipment->route_order }}
                                    </div>

                                    <h4 class="text-lg font-bold text-slate-900">
                                        {{ $shipment->order->customer_name ?? '-' }}
                                    </h4>

                                    <p class="text-sm text-slate-600">
                                        {{ $shipment->order->delivery_address ?? '-' }}
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        @if ($tripShipment->route_order === 1)
                                            Dari gudang:
                                        @else
                                            Dari titik sebelumnya:
                                        @endif
                                        {{ $tripShipment->distance_from_previous_km ?? 0 }} km
                                    </p>

                                    @if ($shipment->google_maps_url)
                                        <a href="{{ $shipment->google_maps_url }}" target="_blank"
                                           class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500">
                                            Buka Maps
                                        </a>
                                    @endif

                                    <div class="pt-2">
                                        <p class="mb-2 text-sm font-semibold text-slate-700">Item:</p>
                                        <div class="space-y-1 text-sm text-slate-600">
                                            @foreach ($shipment->items as $item)
                                                <div>
                                                    {{ $item->product->name ?? '-' }}
                                                    <span class="font-semibold">
                                                        ({{ rtrim(rtrim(number_format($item->qty, 2, '.', ''), '0'), '.') }})
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    @if ($shipment->status === 'completed')
                                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                            ✅ Selesai
                                        </span>
                                    @else
                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                            {{ ucfirst($shipment->status) }}
                                        </span>
                                    @endif

                                    @if ($trip->status === 'on_trip' && $shipment->status !== 'completed')
                                        <form action="{{ route('driver.delivery-trips.complete-stop', [$trip, $tripShipment->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full rounded-2xl bg-emerald-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                                                Selesaikan Stop
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol "Selesaikan Trip" lama dihapus — digantikan oleh Return Home dan Sampai di Gudang di atas --}}
            </div>
        @empty
            <div class="glass-panel rounded-[24px] border border-white/50 p-8 text-center text-sm text-slate-500 shadow-lg">
                Belum ada trip aktif.
            </div>
        @endforelse
    </div>

    <div>
        {{ $trips->links() }}
    </div>
</div>
@endsection