<div class="grid gap-6 md:grid-cols-2" x-data="{
    hargaModal: {{ old('harga_modal', $product->harga_modal ?? 0) }},
    hargaJual: {{ old('harga_jual', $product->harga_jual ?? 0) }},
    formatRupiah(value) {
        return 'Rp ' + new Intl.NumberFormat('id-ID', { minimumFractionDigits: 0 }).format(value);
    },
    get keuntungan() {
        const modal = parseFloat(this.hargaModal) || 0;
        const jual = parseFloat(this.hargaJual) || 0;
        return jual - modal;
    },
    get margin() {
        const modal = parseFloat(this.hargaModal) || 0;
        const jual = parseFloat(this.hargaJual) || 0;
        if (jual <= 0) return 0;
        return ((jual - modal) / jual) * 100;
    }
}">
    <div class="md:col-span-2">
        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
            Nama Barang
        </label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name ?? '') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: Gas Elpiji 3 Kg"
        >
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sku" class="mb-2 block text-sm font-semibold text-slate-700">
            SKU
        </label>
        <input
            type="text"
            id="sku"
            name="sku"
            value="{{ old('sku', $product->sku ?? '') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: LPG-3KG"
        >
        @error('sku')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="unit_id" class="mb-2 block text-sm font-semibold text-slate-700">
            Satuan
        </label>
        <select
            id="unit_id"
            name="unit_id"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
        >
            <option value="">Pilih satuan</option>
            @foreach ($units as $unit)
                <option value="{{ $unit->id }}" @selected(old('unit_id', $product->unit_id ?? '') == $unit->id)>
                    {{ $unit->name }} ({{ $unit->symbol }})
                </option>
            @endforeach
        </select>
        @error('unit_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="weight_kg" class="mb-2 block text-sm font-semibold text-slate-700">
            Berat (Kg)
        </label>
        <input
            type="number"
            step="0.01"
            id="weight_kg"
            name="weight_kg"
            value="{{ old('weight_kg', $product->weight_kg ?? '') }}"
            onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: 3"
        >
        @error('weight_kg')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="minimum_stock" class="mb-2 block text-sm font-semibold text-slate-700">
            Minimum Stok
        </label>
        <input
            type="number"
            step="0.01"
            id="minimum_stock"
            name="minimum_stock"
            value="{{ old('minimum_stock', $product->minimum_stock ?? 10) }}"
            onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: 10"
        >
        @error('minimum_stock')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="harga_modal" class="mb-2 block text-sm font-semibold text-slate-700">
            Harga Modal
        </label>
        <div class="relative rounded-2xl">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-semibold text-slate-400">Rp</span>
            <input
                type="number"
                step="1"
                id="harga_modal"
                name="harga_modal"
                x-model="hargaModal"
                onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
                class="w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200 font-medium text-slate-800"
                placeholder="0"
            >
        </div>
        @error('harga_modal')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="harga_jual" class="mb-2 block text-sm font-semibold text-slate-700">
            Harga Jual
        </label>
        <div class="relative rounded-2xl">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-sm font-semibold text-slate-400">Rp</span>
            <input
                type="number"
                step="1"
                id="harga_jual"
                name="harga_jual"
                x-model="hargaJual"
                onkeydown="if(['e', 'E', '+', '-'].includes(event.key)) event.preventDefault();"
                class="w-full rounded-2xl border border-slate-200 bg-white pl-11 pr-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200 font-medium text-slate-800"
                placeholder="0"
            >
        </div>
        @error('harga_jual')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2 grid grid-cols-2 gap-4 rounded-2xl bg-slate-50 border border-slate-200/60 p-4">
        <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Estimasi Keuntungan</span>
            <span class="text-lg font-bold text-slate-900" x-text="formatRupiah(keuntungan)"></span>
        </div>
        <div>
            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider block">Margin Keuntungan</span>
            <span class="text-lg font-bold" :class="margin >= 20 ? 'text-emerald-600' : (margin > 0 ? 'text-blue-600' : 'text-slate-900')" x-text="margin.toFixed(2) + '%'"></span>
        </div>
    </div>

    <div class="flex items-end pb-3">
        <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $product->is_active ?? true))
                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
            >
            Barang aktif
        </label>
    </div>
</div>