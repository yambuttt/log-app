@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
            Fleet
        </p>
        <h2 class="text-2xl font-bold text-slate-900">
            Tambah Kapasitas Kendaraan
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Atur kapasitas kendaraan berdasarkan produk gas.
        </p>
    </div>

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <form action="{{ route('admin.vehicle-capacities.store') }}" method="POST" class="space-y-6">
            @csrf

            @include('admin.vehicle-capacities._form')

            <div class="flex flex-col gap-3 pt-4 sm:flex-row">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800"
                >
                    Simpan Kapasitas
                </button>

                <a
                    href="{{ route('admin.vehicle-capacities.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection