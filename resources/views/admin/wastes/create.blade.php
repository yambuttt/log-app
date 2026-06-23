@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
            Inventory
        </p>
        <h2 class="text-2xl font-bold text-slate-900">
            Tambah Waste
        </h2>
        <p class="mt-1 text-sm text-slate-500">
            Catat pengurangan stok karena waste.
        </p>
    </div>

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <form action="{{ route('admin.wastes.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label for="waste_date" class="mb-2 block text-sm font-semibold text-slate-700">
                        Tanggal
                    </label>
                    <input
                        type="date"
                        id="waste_date"
                        name="waste_date"
                        value="{{ old('waste_date', now()->format('Y-m-d')) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                    @error('waste_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="warehouse_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Gudang
                    </label>
                    <select
                        id="warehouse_id"
                        name="warehouse_id"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">Pilih gudang</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @selected(old('warehouse_id') == $warehouse->id)>
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('warehouse_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="product_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Barang
                    </label>
                    <select
                        id="product_id"
                        name="product_id"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                    >
                        <option value="">Pilih barang</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" @selected(old('product_id') == $product->id)>
                                {{ $product->name }} - {{ $product->sku }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="qty" class="mb-2 block text-sm font-semibold text-slate-700">
                        Qty
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        id="qty"
                        name="qty"
                        value="{{ old('qty') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        placeholder="Contoh: 1"
                    >
                    @error('qty')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="reason" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alasan Waste
                    </label>
                    <input
                        type="text"
                        id="reason"
                        name="reason"
                        value="{{ old('reason') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        placeholder="Contoh: Bocor / Rusak / Hilang"
                    >
                    @error('reason')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="notes" class="mb-2 block text-sm font-semibold text-slate-700">
                        Catatan
                    </label>
                    <textarea
                        id="notes"
                        name="notes"
                        rows="4"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                        placeholder="Catatan tambahan"
                    >{{ old('notes') }}</textarea>
                </div>
            </div>

            <div class="flex flex-col gap-3 pt-4 sm:flex-row">
                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800"
                >
                    Simpan Waste
                </button>

                <a
                    href="{{ route('admin.wastes.index') }}"
                    class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                >
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection