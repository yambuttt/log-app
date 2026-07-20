@extends('layouts.driver')

@section('content')
    <div class="space-y-6">
        <section class="animate-fade-up">
            <div
                class="overflow-hidden rounded-[28px] bg-gradient-to-br from-amber-950 via-amber-900 to-orange-900 px-6 py-8 text-white shadow-2xl sm:px-8 lg:px-10">
                <div class="grid items-center gap-8 lg:grid-cols-[1.5fr_1fr]">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-[0.24em] text-amber-200/90">Driver Overview</p>
                        <h2 class="mt-3 text-3xl font-bold leading-tight sm:text-4xl">
                            Selamat bertugas, {{ auth()->user()->name ?? 'Driver' }}
                        </h2>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-amber-100/85 sm:text-base">
                            Pantau tugas pengiriman, status perjalanan, dan daftar paket dengan tampilan yang cepat dan
                            nyaman di perangkat mobile.
                        </p>
                    </div>

                    @if(isset($todayAssignment) && $todayAssignment && $todayAssignment->vehicle)
                        <section class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Kendaraan Hari Ini</h3>
                                    <p class="mt-1 text-sm text-slate-500">Kendaraan yang sedang kamu gunakan untuk operasional.
                                    </p>
                                </div>

                                <a href="{{ route('driver.vehicle-assignment.index') }}"
                                    class="inline-flex items-center justify-center rounded-2xl bg-amber-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-800">
                                    Ubah Kendaraan
                                </a>
                            </div>

                            <div class="mt-6 grid gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <p class="text-sm text-slate-500">Nama Kendaraan</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $todayAssignment->vehicle->name }}</p>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <p class="text-sm text-slate-500">Plat Nomor</p>
                                    <p class="mt-1 font-semibold text-slate-900">{{ $todayAssignment->vehicle->plate_number }}
                                    </p>
                                </div>

                                <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                    <p class="text-sm text-slate-500">Jenis</p>
                                    <p class="mt-1 font-semibold text-slate-900">
                                        {{ $todayAssignment->vehicle->vehicle_type === 'small' ? 'Kecil' : 'Besar' }}
                                    </p>
                                </div>
                            </div>
                        </section>
                    @else
                        <section class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Kendaraan Hari Ini</h3>
                                    <p class="mt-1 text-sm text-slate-500">Kamu belum memilih kendaraan untuk operasional hari
                                        ini.</p>
                                </div>

                                <a href="{{ route('driver.vehicle-assignment.index') }}"
                                    class="inline-flex items-center justify-center rounded-2xl bg-amber-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-800">
                                    Pilih Kendaraan
                                </a>
                            </div>
                        </section>
                    @endif

                    <div class="grid grid-cols-2 gap-4">
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-amber-100">Tugas Hari Ini</p>
                            <h3 class="mt-2 text-3xl font-bold">{{ $tugasHariIni }}</h3>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-amber-100">Dalam Perjalanan</p>
                            <h3 class="mt-2 text-3xl font-bold">{{ $dalamPerjalananCount }}</h3>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-amber-100">Terkirim</p>
                            <h3 class="mt-2 text-3xl font-bold">{{ $terkirimCount }}</h3>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                            <p class="text-sm text-amber-100">Pending</p>
                            <h3 class="mt-2 text-3xl font-bold">{{ $pendingCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 lg:grid-cols-3">
            <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg lg:col-span-2">
                <h3 class="text-lg font-bold text-slate-900">Tugas Pengiriman</h3>
                <p class="mt-1 text-sm text-slate-500">Daftar tugas utama hari ini.</p>

                <div class="mt-6 space-y-4">
                    @forelse ($activeShipments as $shipment)
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $shipment->shipment_number }}</p>
                                    <p class="mt-1 text-sm text-slate-500">{{ $shipment->order->delivery_address ?? '-' }}</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Customer: {{ $shipment->order->customer_name ?? '-' }}</p>
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-semibold 
                                    {{ $shipment->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 
                                       ($shipment->status === 'on_delivery' ? 'bg-amber-100 text-amber-700' : 
                                       ($shipment->status === 'assigned' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-700')) }}">
                                    {{ $shipment->status === 'completed' ? 'Terkirim' : 
                                       ($shipment->status === 'on_delivery' ? 'Dalam Perjalanan' : 
                                       ($shipment->status === 'assigned' ? 'Ditugaskan' : 'Pending')) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-sm text-slate-500 py-8 bg-white/50 rounded-2xl border border-dashed border-slate-200">
                            Belum ada tugas pengiriman aktif hari ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
                <h3 class="text-lg font-bold text-slate-900">Aksi Cepat</h3>
                <div class="mt-6 space-y-3">
                    <a href="{{ route('driver.shipments.index') }}"
                        class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 hover:shadow-md transition hover:-translate-y-0.5">
                        <div>
                            <p class="font-semibold text-slate-900">Lihat Tugas Hari Ini</p>
                            <p class="text-sm text-slate-500">Daftar pengiriman aktif</p>
                        </div>
                        <span>→</span>
                    </a>
                    <a href="{{ route('driver.delivery-trips.index') }}"
                        class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 hover:shadow-md transition hover:-translate-y-0.5">
                        <div>
                            <p class="font-semibold text-slate-900">Update Status</p>
                            <p class="text-sm text-slate-500">Perbarui perjalanan</p>
                        </div>
                        <span>→</span>
                    </a>
                    <a href="{{ route('driver.shipments.index') }}"
                        class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 hover:shadow-md transition hover:-translate-y-0.5">
                        <div>
                            <p class="font-semibold text-slate-900">Riwayat Pengiriman</p>
                            <p class="text-sm text-slate-500">Lihat tugas selesai</p>
                        </div>
                        <span>→</span>
                    </a>
                </div>
            </div>
        </section>
    </div>
@endsection