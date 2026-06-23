@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <section class="animate-fade-up">
        <div class="overflow-hidden rounded-[28px] bg-gradient-to-br from-emerald-950 via-emerald-900 to-lime-900 px-6 py-8 text-white shadow-2xl sm:px-8 lg:px-10">
            <div class="grid items-center gap-8 lg:grid-cols-[1.5fr_1fr]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-emerald-200/90">Warehouse Overview</p>
                    <h2 class="mt-3 text-3xl font-bold leading-tight sm:text-4xl">
                        Selamat datang, {{ auth()->user()->name ?? 'Tim Gudang' }}
                    </h2>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-emerald-100/85 sm:text-base">
                        Kelola stok, pergerakan barang, dan proses persiapan pengiriman dalam satu dashboard operasional.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-emerald-100">Total SKU</p>
                        <h3 class="mt-2 text-3xl font-bold">320</h3>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-emerald-100">Stok Rendah</p>
                        <h3 class="mt-2 text-3xl font-bold">14</h3>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-emerald-100">Barang Masuk</p>
                        <h3 class="mt-2 text-3xl font-bold">58</h3>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                        <p class="text-sm text-emerald-100">Siap Kirim</p>
                        <h3 class="mt-2 text-3xl font-bold">27</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-3">
        <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg lg:col-span-2">
            <h3 class="text-lg font-bold text-slate-900">Aktivitas Gudang</h3>
            <p class="mt-1 text-sm text-slate-500">Ringkasan operasional hari ini.</p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-sm text-slate-500">Receiving</p>
                    <h4 class="mt-2 text-2xl font-bold text-slate-900">22</h4>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-sm text-slate-500">Putaway</p>
                    <h4 class="mt-2 text-2xl font-bold text-slate-900">18</h4>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-sm text-slate-500">Picking</p>
                    <h4 class="mt-2 text-2xl font-bold text-slate-900">31</h4>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <p class="text-sm text-slate-500">Packing</p>
                    <h4 class="mt-2 text-2xl font-bold text-slate-900">19</h4>
                </div>
            </div>
        </div>

        <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
            <h3 class="text-lg font-bold text-slate-900">Aksi Cepat</h3>
            <div class="mt-6 space-y-3">
                <a href="#" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 hover:shadow-md">
                    <div>
                        <p class="font-semibold text-slate-900">Input Barang Masuk</p>
                        <p class="text-sm text-slate-500">Catat receiving</p>
                    </div>
                    <span>→</span>
                </a>
                <a href="#" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 hover:shadow-md">
                    <div>
                        <p class="font-semibold text-slate-900">Update Stok</p>
                        <p class="text-sm text-slate-500">Perbarui kuantitas</p>
                    </div>
                    <span>→</span>
                </a>
                <a href="#" class="flex items-center justify-between rounded-2xl border border-slate-200 bg-white px-4 py-4 hover:shadow-md">
                    <div>
                        <p class="font-semibold text-slate-900">Siapkan Pengiriman</p>
                        <p class="text-sm text-slate-500">Picking dan packing</p>
                    </div>
                    <span>→</span>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection