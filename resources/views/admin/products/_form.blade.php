<div class="grid gap-6 md:grid-cols-2">
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
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: 3"
        >
        @error('weight_kg')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center">
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