@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Warehouse Inventory</p>
        <h2 class="text-2xl font-bold text-slate-900">Tambah Stock Opname</h2>
        <p class="mt-1 text-sm text-slate-500">Input stock opname untuk gudang Anda.</p>
    </div>

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
        <form action="{{ route('warehouse.stock-opnames.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal</label>
                    <input type="date" name="opname_date" value="{{ old('opname_date', now()->format('Y-m-d')) }}"
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100">
                    @error('opname_date')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Barang</label>
                    <select name="product_id"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100">
                        <option value="">Pilih barang</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                {{ $product->name }} - {{ $product->sku }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Stok Fisik</label>
                    <input type="number" step="0.01" name="physical_qty" value="{{ old('physical_qty') }}"
                           class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                           placeholder="Contoh: 8">
                    @error('physical_qty')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan</label>
                    <textarea name="notes" rows="4"
                              class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                              placeholder="Catatan tambahan">{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-4 sm:flex-row">
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-emerald-900 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-800">
                    Simpan Stock Opname
                </button>

                <a href="{{ route('warehouse.stock-opnames.index') }}"
                   class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection