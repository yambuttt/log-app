<div class="grid gap-6 md:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">Nama</label>
        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Nama user"
        >
        @error('name')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="{{ old('email') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="email@perusahaan.com"
        >
        @error('email')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Minimal 6 karakter"
        >
        @error('password')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
        <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Ulangi password"
        >
    </div>

    <div>
        <label for="role" class="mb-2 block text-sm font-semibold text-slate-700">Role</label>
        <select
            id="role"
            name="role"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
        >
            <option value="">Pilih role</option>
            <option value="admin" @selected(old('role') === 'admin')>Admin</option>
            <option value="warehouse" @selected(old('role') === 'warehouse')>Gudang</option>
            <option value="driver" @selected(old('role') === 'driver')>Driver</option>
        </select>
        @error('role')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-semibold text-slate-700">Nomor HP</label>
        <input
            type="text"
            id="phone"
            name="phone"
            value="{{ old('phone') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="08xxxx"
        >
        @error('phone')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label for="warehouse_id" class="mb-2 block text-sm font-semibold text-slate-700">Gudang (untuk role gudang)</label>
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
        <label for="license_number" class="mb-2 block text-sm font-semibold text-slate-700">Nomor SIM (untuk driver)</label>
        <input
            type="text"
            id="license_number"
            name="license_number"
            value="{{ old('license_number') }}"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Contoh: SIM B1"
        >
    </div>

    <div>
        <label for="address" class="mb-2 block text-sm font-semibold text-slate-700">Alamat Driver</label>
        <textarea
            id="address"
            name="address"
            rows="3"
            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
            placeholder="Alamat lengkap driver"
        >{{ old('address') }}</textarea>
    </div>

    <div class="md:col-span-2 flex items-center">
        <label class="inline-flex items-center gap-3 text-sm font-medium text-slate-700">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(old('is_active', true))
                class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
            >
            User aktif
        </label>
    </div>
</div>