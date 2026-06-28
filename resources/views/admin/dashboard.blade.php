@extends('layouts.app')

@section('content')
<div class="space-y-6">
    @if ($lowStocks->isNotEmpty())
        <section class="animate-fade-up">
            <div class="rounded-[28px] border border-amber-200 bg-amber-50/70 p-6 shadow-lg shadow-amber-100/30 backdrop-blur-xl">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-2xl">
                        ⚠️
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-amber-900">Peringatan: Stok Menipis!</h3>
                        <p class="mt-1 text-sm text-amber-700">Beberapa barang telah menyentuh atau berada di bawah batas minimum stok. Segera refill untuk mencegah kehabisan stok!</p>
                        
                        <div class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($lowStocks as $stock)
                                <div class="rounded-2xl border border-amber-200/60 bg-white p-4 shadow-sm transition hover:shadow-md">
                                    <p class="font-bold text-slate-900">{{ $stock->product->name }}</p>
                                    <p class="text-xs text-slate-500 mt-1">Gudang: {{ $stock->warehouse->name }}</p>
                                    <div class="mt-3 flex items-center justify-between">
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-xs font-bold text-red-700">
                                            Stok: {{ rtrim(rtrim(number_format($stock->qty, 2, '.', ''), '0'), '.') }} {{ $stock->product->unit->symbol ?? '' }}
                                        </span>
                                        <span class="text-xs font-semibold text-slate-500">
                                            Min: {{ rtrim(rtrim(number_format($stock->product->minimum_stock, 2, '.', ''), '0'), '.') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="animate-fade-up">
        <div class="overflow-hidden rounded-[28px] bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-6 py-8 text-white shadow-2xl shadow-slate-900/20 sm:px-8 lg:px-10">
            <div class="grid items-center gap-8 lg:grid-cols-[1.5fr_1fr]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-cyan-300/90">
                        Admin Overview
                    </p>
                    <h2 class="mt-3 text-3xl font-bold leading-tight sm:text-4xl">
                        Selamat datang, {{ auth()->user()->name ?? 'Administrator' }}
                    </h2>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">
                        Pantau stok barang, aktivitas gudang, dan pengiriman dalam satu dashboard yang cepat, rapi, dan mudah digunakan.
                    </p>
 
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.products.index') }}"
                           class="inline-flex items-center gap-2 rounded-2xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 transition hover:-translate-y-0.5 hover:bg-slate-100">
                            Kelola Barang
                        </a>
 
                        <a href="{{ route('admin.delivery-trips.index') }}"
                           class="inline-flex items-center gap-2 rounded-2xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-semibold text-white backdrop-blur transition hover:-translate-y-0.5 hover:bg-white/15">
                            Lihat Pengiriman
                        </a>
                    </div>
                </div>
 
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur animate-soft-float">
                        <p class="text-sm text-slate-300">Total Produk</p>
                        <h3 class="mt-2 text-3xl font-bold">{{ $totalProducts }}</h3>
                        <p class="mt-1 text-xs text-emerald-300">Di master data</p>
                    </div>
 
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-slate-300">Stok Menipis</p>
                        <h3 class="mt-2 text-3xl font-bold">{{ $lowStockCount }}</h3>
                        <p class="mt-1 text-xs text-amber-300">Perlu restock</p>
                    </div>
 
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-slate-300">Pesanan Hari Ini</p>
                        <h3 class="mt-2 text-3xl font-bold">{{ $ordersTodayCount }}</h3>
                        <p class="mt-1 text-xs text-cyan-300">Aktivitas berjalan</p>
                    </div>
 
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur animate-soft-float">
                        <p class="text-sm text-slate-300">Driver Aktif</p>
                        <h3 class="mt-2 text-3xl font-bold">{{ $activeDriversCount }}</h3>
                        <p class="mt-1 text-xs text-fuchsia-300">Sedang bertugas</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="glass-panel animate-fade-up rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50 lg:col-span-2">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Ringkasan Aktivitas</h3>
                    <p class="mt-1 text-sm text-slate-500">Gambaran singkat operasional hari ini.</p>
                </div>
                <span class="rounded-full bg-slate-900 px-3 py-1 text-xs font-semibold text-white">
                    Hari ini
                </span>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-100 text-cyan-700">
                        📦
                    </div>
                    <p class="text-sm text-slate-500">Barang Masuk</p>
                    <h4 class="mt-1 text-2xl font-bold text-slate-900">124</h4>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-700">
                        🚚
                    </div>
                    <p class="text-sm text-slate-500">Siap Kirim</p>
                    <h4 class="mt-1 text-2xl font-bold text-slate-900">21</h4>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                        ✅
                    </div>
                    <p class="text-sm text-slate-500">Terkirim</p>
                    <h4 class="mt-1 text-2xl font-bold text-slate-900">17</h4>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-1 hover:shadow-lg">
                    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                        ⚠️
                    </div>
                    <p class="text-sm text-slate-500">Pending</p>
                    <h4 class="mt-1 text-2xl font-bold text-slate-900">4</h4>
                </div>
            </div>
        </div>

        <div class="glass-panel animate-fade-up rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Quick Actions</h3>
                <p class="mt-1 text-sm text-slate-500">Akses cepat menu utama.</p>
            </div>

            <div class="space-y-3">
                <a href="#" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                    <div>
                        <p class="font-semibold text-slate-900">Tambah Barang</p>
                        <p class="text-sm text-slate-500">Input produk baru</p>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>

                <a href="#" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                    <div>
                        <p class="font-semibold text-slate-900">Stok Masuk</p>
                        <p class="text-sm text-slate-500">Catat barang masuk</p>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>

                <a href="#" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                    <div>
                        <p class="font-semibold text-slate-900">Buat Pengiriman</p>
                        <p class="text-sm text-slate-500">Atur pesanan keluar</p>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>

                <a href="#" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 transition hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md">
                    <div>
                        <p class="font-semibold text-slate-900">Kelola Driver</p>
                        <p class="text-sm text-slate-500">Monitor tim pengiriman</p>
                    </div>
                    <span class="text-slate-400">→</span>
                </a>
            </div>
        </div>
    </section>

    <section class="grid gap-6 xl:grid-cols-2">
        <div class="glass-panel animate-fade-up rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Pengiriman Terbaru</h3>
                    <p class="mt-1 text-sm text-slate-500">Status order yang sedang berjalan.</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-start justify-between rounded-2xl border border-slate-200 bg-white p-4">
                    <div>
                        <p class="font-semibold text-slate-900">INV-2026-001</p>
                        <p class="mt-1 text-sm text-slate-500">Driver: Budi Santoso</p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">
                        Dalam Perjalanan
                    </span>
                </div>

                <div class="flex items-start justify-between rounded-2xl border border-slate-200 bg-white p-4">
                    <div>
                        <p class="font-semibold text-slate-900">INV-2026-002</p>
                        <p class="mt-1 text-sm text-slate-500">Driver: Andi Pratama</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Terkirim
                    </span>
                </div>

                <div class="flex items-start justify-between rounded-2xl border border-slate-200 bg-white p-4">
                    <div>
                        <p class="font-semibold text-slate-900">INV-2026-003</p>
                        <p class="mt-1 text-sm text-slate-500">Driver: Rian Saputra</p>
                    </div>
                    <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">
                        Disiapkan
                    </span>
                </div>
            </div>
        </div>

        <div class="glass-panel animate-fade-up rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-slate-900">Info Sistem</h3>
                <p class="mt-1 text-sm text-slate-500">Ringkasan status aplikasi internal.</p>
            </div>

            <div class="space-y-4">
                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4">
                    <div>
                        <p class="font-semibold text-slate-900">Status Server</p>
                        <p class="text-sm text-slate-500">Sistem berjalan normal</p>
                    </div>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Online
                    </span>
                </div>

                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4">
                    <div>
                        <p class="font-semibold text-slate-900">Sinkronisasi Data</p>
                        <p class="text-sm text-slate-500">Terakhir diperbarui 5 menit lalu</p>
                    </div>
                    <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-semibold text-cyan-700">
                        Aktif
                    </span>
                </div>

                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white p-4">
                    <div>
                        <p class="font-semibold text-slate-900">Pengguna Login</p>
                        <p class="text-sm text-slate-500">Admin dan staff aktif hari ini</p>
                    </div>
                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-700">
                        14 User
                    </span>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection