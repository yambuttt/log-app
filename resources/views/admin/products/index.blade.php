@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                Inventory
            </p>
            <h2 class="text-2xl font-bold text-slate-900">
                Master Barang
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Kelola data produk inventory perusahaan.
            </p>
        </div>

        <a
            href="{{ route('admin.products.create') }}"
            class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:-translate-y-0.5 hover:bg-slate-800"
        >
            + Tambah Barang
        </a>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg shadow-slate-200/50">
        <div class="overflow-x-auto">
            <table class="min-w-full border-separate border-spacing-y-3">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Nama</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">SKU</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Satuan</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Berat</th>
                        <th class="px-4 py-2 text-left text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Status</th>
                        <th class="px-4 py-2 text-right text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr class="rounded-2xl bg-white shadow-sm">
                            <td class="rounded-l-2xl px-4 py-4 font-semibold text-slate-900">
                                {{ $product->name }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ $product->sku }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ $product->unit->name ?? '-' }}
                            </td>
                            <td class="px-4 py-4 text-sm text-slate-600">
                                {{ $product->weight_kg ? rtrim(rtrim(number_format($product->weight_kg, 2, '.', ''), '0'), '.') . ' Kg' : '-' }}
                            </td>
                            <td class="px-4 py-4">
                                @if ($product->is_active)
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="rounded-full bg-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="rounded-r-2xl px-4 py-4 text-right">
                                <a
                                    href="{{ route('admin.products.edit', $product) }}"
                                    class="inline-flex items-center rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                                >
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-slate-500">
                                Belum ada data barang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection