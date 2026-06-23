@extends('layouts.warehouse')

@section('content')
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">
                Warehouse Delivery
            </p>
            <h2 class="text-2xl font-bold text-slate-900">
                Tambah Pesanan
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Buat pesanan customer baru untuk gudang Anda.
            </p>
        </div>

        <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
            <form action="{{ route('warehouse.orders.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Pesanan</label>
                        <input type="date" name="order_date" value="{{ old('order_date', now()->format('Y-m-d')) }}"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100">
                        @error('order_date')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Nama Customer</label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                            placeholder="Nama customer">
                        @error('customer_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">No. HP Customer</label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                            placeholder="08xxxx">
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat Pengiriman</label>
                        <textarea name="delivery_address" rows="3"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                            placeholder="Alamat lengkap pengiriman">{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Latitude Pengiriman</label>
                            <input id="delivery_latitude" type="text" name="delivery_latitude"
                                value="{{ old('delivery_latitude', '-7.2575') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none">
                            @error('delivery_latitude')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Longitude Pengiriman</label>
                            <input id="delivery_longitude" type="text" name="delivery_longitude"
                                value="{{ old('delivery_longitude', '112.7521') }}"
                                class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none">
                            @error('delivery_longitude')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Pilih Lokasi Customer</label>
                        <div id="delivery-map" class="h-[420px] w-full rounded-2xl border border-slate-200"></div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan</label>
                        <textarea name="notes" rows="3"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                            placeholder="Catatan tambahan">{{ old('notes') }}</textarea>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Item Pesanan</h3>
                        <button type="button" id="add-item"
                            class="rounded-xl bg-emerald-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-800">
                            + Tambah Item
                        </button>
                    </div>

                    @error('items')
                        <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div id="items-wrapper" class="space-y-4">
                        <div
                            class="grid gap-4 rounded-2xl border border-slate-200 p-4 md:grid-cols-[1fr_220px_auto] item-row">
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Produk</label>
                                <select name="items[0][product_id]"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100">
                                    <option value="">Pilih produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" @selected(old('items.0.product_id') == $product->id)>
                                            {{ $product->name }} - {{ $product->sku }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('items.0.product_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Qty</label>
                                <input type="number" step="0.01" name="items[0][qty]" value="{{ old('items.0.qty') }}"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                                    placeholder="Contoh: 10">
                                @error('items.0.qty')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-end">
                                <button type="button"
                                    class="remove-item rounded-xl border border-red-200 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-4 sm:flex-row">
                    <button type="submit"
                        class="inline-flex items-center justify-center rounded-2xl bg-emerald-900 px-5 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-emerald-800">
                        Simpan Pesanan
                    </button>

                    <a href="{{ route('warehouse.orders.index') }}"
                        class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        const addItemButton = document.getElementById('add-item');
        const itemsWrapper = document.getElementById('items-wrapper');

        addItemButton.addEventListener('click', function () {
            const index = itemsWrapper.querySelectorAll('.item-row').length;

            const template = `
                <div class="grid gap-4 rounded-2xl border border-slate-200 p-4 md:grid-cols-[1fr_220px_auto] item-row">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Produk</label>
                        <select
                            name="items[${index}][product_id]"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                        >
                            <option value="">Pilih produk</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} - {{ $product->sku }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-slate-700">Qty</label>
                        <input
                            type="number"
                            step="0.01"
                            name="items[${index}][qty]"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none focus:border-emerald-900 focus:ring-4 focus:ring-emerald-100"
                            placeholder="Contoh: 10"
                        >
                    </div>

                    <div class="flex items-end">
                        <button
                            type="button"
                            class="remove-item rounded-xl border border-red-200 px-4 py-3 text-sm font-semibold text-red-600 hover:bg-red-50"
                        >
                            Hapus
                        </button>
                    </div>
                </div>
            `;

            itemsWrapper.insertAdjacentHTML('beforeend', template);
        });

        itemsWrapper.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-item')) {
                const rows = itemsWrapper.querySelectorAll('.item-row');

                if (rows.length > 1) {
                    e.target.closest('.item-row').remove();
                }
            }
        });
    </script>
    @push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const deliveryLatInput = document.getElementById('delivery_latitude');
    const deliveryLngInput = document.getElementById('delivery_longitude');

    const mapLat = parseFloat(deliveryLatInput.value) || -7.2575;
    const mapLng = parseFloat(deliveryLngInput.value) || 112.7521;

    const deliveryMap = L.map('delivery-map').setView([mapLat, mapLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(deliveryMap);

    let deliveryMarker = L.marker([mapLat, mapLng], { draggable: true }).addTo(deliveryMap);

    function updateDeliveryInputs(lat, lng) {
        deliveryLatInput.value = lat.toFixed(7);
        deliveryLngInput.value = lng.toFixed(7);
    }

    deliveryMap.on('click', function(e) {
        deliveryMarker.setLatLng(e.latlng);
        updateDeliveryInputs(e.latlng.lat, e.latlng.lng);
    });

    deliveryMarker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        updateDeliveryInputs(pos.lat, pos.lng);
    });
</script>
@endpush
@endsection