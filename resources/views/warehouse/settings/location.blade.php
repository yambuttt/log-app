@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Warehouse Settings</p>
        <h2 class="text-2xl font-bold text-slate-900">Lokasi Gudang</h2>
        <p class="mt-1 text-sm text-slate-500">Tentukan titik gudang langsung di peta.</p>
    </div>

    @if (session('success'))
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
        <form action="{{ route('warehouse.settings.location.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Alamat Gudang</label>
                <textarea name="address" rows="3"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none">{{ old('address', $warehouse->address) }}</textarea>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Latitude</label>
                    <input id="latitude" type="text" name="latitude"
                        value="{{ old('latitude', $warehouse->latitude ?? '-7.2575') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Longitude</label>
                    <input id="longitude" type="text" name="longitude"
                        value="{{ old('longitude', $warehouse->longitude ?? '112.7521') }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none">
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Pilih Lokasi di Peta</label>
                <div id="warehouse-map" class="h-[420px] w-full rounded-2xl border border-slate-200"></div>
                <p class="mt-2 text-sm text-slate-500">Klik peta untuk memindahkan marker gudang.</p>
            </div>

            <button type="submit"
                class="rounded-2xl bg-emerald-900 px-5 py-3 text-sm font-semibold text-white">
                Simpan Lokasi Gudang
            </button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    const initialLat = parseFloat(latInput.value) || -7.2575;
    const initialLng = parseFloat(lngInput.value) || 112.7521;

    const map = L.map('warehouse-map').setView([initialLat, initialLng], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = L.marker([initialLat, initialLng], { draggable: true }).addTo(map);

    function updateInputs(lat, lng) {
        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);
    }

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        updateInputs(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function(e) {
        const pos = e.target.getLatLng();
        updateInputs(pos.lat, pos.lng);
    });
</script>
@endpush