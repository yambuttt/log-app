@extends('layouts.driver')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                Delivery
            </p>
            <h2 class="text-2xl font-bold text-slate-900">
                Tugas Pengiriman
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Daftar tugas pengiriman yang ditugaskan kepada Anda.
            </p>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($shipments as $shipment)
                <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="space-y-2">
                            <div class="text-sm text-slate-500">{{ $shipment->shipment_number }}</div>
                            <h3 class="text-xl font-bold text-slate-900">
                                {{ $shipment->order->customer_name ?? '-' }}
                            </h3>
                            <p class="text-sm text-slate-600">
                                {{ $shipment->order->delivery_address ?? '-' }}
                            </p>
                            <p class="text-sm text-slate-500">
                                Tanggal: {{ \Carbon\Carbon::parse($shipment->shipment_date)->format('d M Y') }}
                            </p>

                            @if ($shipment->vehicle)
                                <p class="text-sm text-slate-500">
                                    Kendaraan: <span class="font-semibold text-slate-800">{{ $shipment->vehicle->name }} -
                                        {{ $shipment->vehicle->plate_number }}</span>
                                </p>
                            @endif

                            <div class="pt-2">
                                <p class="mb-2 text-sm font-semibold text-slate-700">Item:</p>
                                <div class="space-y-1 text-sm text-slate-600">
                                    @foreach ($shipment->items as $item)
                                        <div>
                                            {{ $item->product->name ?? '-' }}
                                            <span
                                                class="font-semibold">({{ rtrim(rtrim(number_format($item->qty, 2, '.', ''), '0'), '.') }})</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            @if ($shipment->estimated_distance_km || $shipment->estimated_duration_minutes)
                                <p class="text-sm text-slate-500">
                                    Estimasi: {{ $shipment->estimated_distance_km ?? '-' }} km •
                                    {{ $shipment->estimated_duration_minutes ?? '-' }} menit
                                </p>
                            @endif

                            @if ($shipment->google_maps_url)
                                <a href="{{ $shipment->google_maps_url }}" target="_blank"
                                    class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-500">
                                    Buka Google Maps
                                </a>
                            @endif
                        </div>

                        <div class="space-y-3">
                            <div>
                                @if ($shipment->status === 'assigned')
                                    <span
                                        class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">Assigned</span>
                                @elseif ($shipment->status === 'on_delivery')
                                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">On
                                        Delivery</span>
                                @elseif ($shipment->status === 'delivered')
                                    <span
                                        class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">Delivered</span>
                                @elseif ($shipment->status === 'returning')
                                    <span
                                        class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">Returning</span>
                                @else
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ ucfirst($shipment->status) }}
                                    </span>
                                @endif
                            </div>

                            @if ($shipment->status === 'assigned')
                                <form action="{{ route('driver.shipments.start', $shipment) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-2xl bg-amber-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-800">
                                        Mulai Pengiriman
                                    </button>
                                </form>
                            @elseif ($shipment->status === 'on_delivery')
                                <form action="{{ route('driver.shipments.delivered', $shipment) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-2xl bg-emerald-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                                        Selesai Antar
                                    </button>
                                </form>
                            @elseif ($shipment->status === 'delivered')
                                <form action="{{ route('driver.shipments.returned', $shipment) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full rounded-2xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                                        Kembali ke Gudang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="glass-panel rounded-[24px] border border-white/50 p-8 text-center text-sm text-slate-500 shadow-lg">
                    Belum ada tugas pengiriman.
                </div>
            @endforelse
        </div>

        <div>
            {{ $shipments->links() }}
        </div>
    </div>
@endsection