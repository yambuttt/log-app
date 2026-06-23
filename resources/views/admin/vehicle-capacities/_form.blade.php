<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="vehicle_id" class="mb-2 block text-sm font-semibold text-slate-700">Kendaraan</label>
        <select
            id="vehicle_id"
            name="vehicle_id"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
        >
            <option value="">Pilih kendaraan</option>
            @foreach ($vehicles as $vehicle)
                <option value="{{ $vehicle->id }}" @selected(old('vehicle_id') == $vehicle->id)>
                    {{ $vehicle->name }} - {{ $vehicle->plate_number }}
                </option>
            @endforeach
        </select>
        @error('vehicle_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="product_id" class="mb-2 block text-sm font-semibold text-slate-700">Produk</label>
        <select
            id="product_id"
            name="product_id"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
        >
            <option value="">Pilih produk</option>
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
        <label for="max_qty" class="mb-2 block text-sm font-semibold text-slate-700">Kapasitas Maksimal</label>
        <input
            type="number"
            id="max_qty"
            name="max_qty"
            value="{{ old('max_qty') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: 20"
        >
        @error('max_qty')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>