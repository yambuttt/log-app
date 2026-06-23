<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama Kendaraan</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $vehicle->name ?? '') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: L300 Gudang Utama"
        >
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="plate_number" class="mb-2 block text-sm font-semibold text-slate-700">Nomor Plat</label>
        <input
            type="text"
            id="plate_number"
            name="plate_number"
            value="{{ old('plate_number', $vehicle->plate_number ?? '') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm uppercase outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: B 1234 XYZ"
        >
        @error('plate_number')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="vehicle_type" class="mb-2 block text-sm font-semibold text-slate-700">Jenis Kendaraan</label>
        <select
            id="vehicle_type"
            name="vehicle_type"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
        >
            <option value="">Pilih jenis kendaraan</option>
            <option value="small" @selected(old('vehicle_type', $vehicle->vehicle_type ?? '') === 'small')>Kecil</option>
            <option value="large" @selected(old('vehicle_type', $vehicle->vehicle_type ?? '') === 'large')>Besar</option>
        </select>
        @error('vehicle_type')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="fuel_efficiency" class="mb-2 block text-sm font-semibold text-slate-700">Konsumsi Bensin (km/l)</label>
        <input
            type="number"
            step="0.01"
            id="fuel_efficiency"
            name="fuel_efficiency"
            value="{{ old('fuel_efficiency', $vehicle->fuel_efficiency ?? '') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: 12.5"
        >
        @error('fuel_efficiency')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center">
        <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', $vehicle->is_active ?? true))
                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
            >
            Kendaraan aktif
        </label>
    </div>
</div>