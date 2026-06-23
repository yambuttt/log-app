@extends('layouts.warehouse')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm font-semibold uppercase tracking-[0.22em] text-slate-500">Warehouse Delivery</p>
        <h2 class="text-2xl font-bold text-slate-900">Buat Delivery Trip</h2>
        <p class="mt-1 text-sm text-slate-500">Pilih beberapa shipment, lalu sistem akan mengurutkan rute otomatis.</p>
    </div>

    <div class="glass-panel rounded-[24px] border border-white/50 p-6 shadow-lg">
        <form action="{{ route('warehouse.delivery-trips.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Trip</label>
                    <input type="date" name="trip_date" value="{{ old('trip_date', $tripDate) }}"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none">
                    @error('trip_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Driver (Tugas Hari Ini)</label>
                    <select name="driver_user_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none">
                        <option value="">Pilih driver</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" @selected(old('driver_user_id') == $driver->id)>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('driver_user_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Warning Container untuk Validasi dan Rekomendasi Kapasitas -->
            <div id="warning-container" class="hidden"></div>

            <div>
                <label class="mb-3 block text-sm font-semibold text-slate-700">Pilih Shipment</label>
                @error('shipment_ids')
                    <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="space-y-3">
                    @forelse ($shipments as $shipment)
                        @php
                            $qty3 = $shipment->items->where('product.sku', 'LPG-3KG')->sum('qty');
                            $qty5 = $shipment->items->where('product.sku', 'LPG-55KG')->sum('qty');
                            $qty12 = $shipment->items->where('product.sku', 'LPG-12KG')->sum('qty');
                        @endphp
                        <label class="flex items-start gap-3 rounded-2xl border border-slate-200 bg-white p-4 cursor-pointer hover:shadow-sm transition">
                            <input type="checkbox" name="shipment_ids[]" value="{{ $shipment->id }}"
                                class="mt-1 h-4 w-4 rounded border-slate-300"
                                data-qty3kg="{{ $qty3 }}"
                                data-qty5kg="{{ $qty5 }}"
                                data-qty12kg="{{ $qty12 }}"
                                @checked(is_array(old('shipment_ids')) && in_array($shipment->id, old('shipment_ids')))>

                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900">
                                        {{ $shipment->shipment_number }} — {{ $shipment->order->customer_name ?? '-' }}
                                    </span>
                                </div>
                                <div class="text-sm text-slate-500 mt-0.5">
                                    📍 {{ $shipment->order->delivery_address ?? '-' }}
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    @if($qty3 > 0)
                                        <span class="rounded-lg bg-emerald-50 border border-emerald-200 px-2 py-0.5 text-xs text-emerald-800 font-semibold">LPG 3kg: {{ $qty3 }} tabung</span>
                                    @endif
                                    @if($qty5 > 0)
                                        <span class="rounded-lg bg-sky-50 border border-sky-200 px-2 py-0.5 text-xs text-sky-800 font-semibold">LPG 5.5kg: {{ $qty5 }} tabung</span>
                                    @endif
                                    @if($qty12 > 0)
                                        <span class="rounded-lg bg-indigo-50 border border-indigo-200 px-2 py-0.5 text-xs text-indigo-800 font-semibold">LPG 12kg: {{ $qty12 }} tabung</span>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="rounded-2xl border border-dashed border-slate-300 bg-white/60 p-6 text-sm text-slate-500">
                            Belum ada shipment yang siap dimasukkan ke trip pada tanggal ini.
                        </div>
                    @endforelse
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Catatan</label>
                <textarea name="notes" rows="3"
                    class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm outline-none">{{ old('notes') }}</textarea>
            </div>

            <button type="submit"
                class="rounded-2xl bg-emerald-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-emerald-900 shadow-md">
                Simpan Trip & Urutkan Rute (NN)
            </button>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const assignments = @json($assignments);
    const dateInput = document.querySelector('input[name="trip_date"]');
    const driverSelect = document.querySelector('select[name="driver_user_id"]');
    const checkboxes = document.querySelectorAll('input[name="shipment_ids[]"]');
    const warningContainer = document.getElementById('warning-container');
    const submitBtn = document.querySelector('button[type="submit"]');

    function updateDrivers() {
        const selectedDate = dateInput.value;
        const previousVal = driverSelect.value;
        
        driverSelect.innerHTML = '<option value="">Pilih driver</option>';
        
        const activeAssignments = assignments.filter(a => a.assignment_date === selectedDate && a.driver.availability_status === 'available');
        
        activeAssignments.forEach(a => {
            const vehicleTypeLabel = a.vehicle.vehicle_type === 'large' ? 'Truk Besar' : 'Pick Up Kecil';
            const option = document.createElement('option');
            option.value = a.driver_user_id;
            option.textContent = `${a.driver.name} (${a.vehicle.name} - ${a.vehicle.plate_number} [${vehicleTypeLabel}])`;
            
            option.dataset.vehicleType = a.vehicle.vehicle_type;
            const capacities = {};
            a.vehicle.capacities.forEach(c => {
                if (c.product) {
                    capacities[c.product.sku] = c.max_qty;
                }
            });
            option.dataset.capacities = JSON.stringify(capacities);
            
            if (previousVal == a.driver_user_id) {
                option.selected = true;
            }
            driverSelect.appendChild(option);
        });

        validateCapacity();
    }

    function validateCapacity() {
        warningContainer.classList.add('hidden');
        warningContainer.innerHTML = '';
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');

        const selectedOption = driverSelect.options[driverSelect.selectedIndex];
        if (!selectedOption || !selectedOption.value) {
            return;
        }

        let total3kg = 0;
        let total5kg = 0;
        let total12kg = 0;

        checkboxes.forEach(cb => {
            if (cb.checked) {
                total3kg += parseFloat(cb.dataset.qty3kg || 0);
                total5kg += parseFloat(cb.dataset.qty5kg || 0);
                total12kg += parseFloat(cb.dataset.qty12kg || 0);
            }
        });

        if (total3kg === 0 && total5kg === 0 && total12kg === 0) {
            return;
        }

        const vehicleType = selectedOption.dataset.vehicleType;
        const capacities = JSON.parse(selectedOption.dataset.capacities || '{}');

        const max3kg = capacities['LPG-3KG'] || 0;
        const max5kg = capacities['LPG-55KG'] || 0;
        const max12kg = capacities['LPG-12KG'] || 0;

        let errorMessages = [];

        if (total3kg > max3kg) {
            errorMessages.push(`LPG 3 Kg: muatan ${total3kg} tabung melebihi kapasitas ${max3kg} tabung.`);
        }
        if (total5kg > max5kg) {
            errorMessages.push(`LPG 5.5 Kg: muatan ${total5kg} tabung melebihi kapasitas ${max5kg} tabung.`);
        }
        if (total12kg > max12kg) {
            errorMessages.push(`LPG 12 Kg: muatan ${total12kg} tabung melebihi kapasitas ${max12kg} tabung.`);
        }

        warningContainer.classList.remove('hidden');

        if (errorMessages.length > 0) {
            warningContainer.innerHTML = `
                <div class="rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
                    <p class="font-bold">❌ Kapasitas Kendaraan Tidak Cukup:</p>
                    <ul class="mt-1 list-disc list-inside space-y-0.5">
                        ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                    </ul>
                </div>
            `;
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            const isSmallLoad = (total12kg <= 8 && total5kg <= 15 && total3kg <= 30);
            
            if (isSmallLoad && vehicleType === 'large') {
                warningContainer.innerHTML = `
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm font-medium text-amber-700">
                        ⚠️ <span class="font-bold">Tidak Direkomendasikan (Pemborosan Armada):</span> Muatan tergolong kecil (3kg: ${total3kg}, 5.5kg: ${total5kg}, 12kg: ${total12kg} tabung) tetapi menggunakan Truk Besar. Disarankan menggunakan Pick Up Kecil jika tersedia untuk menghemat biaya operasional.
                    </div>
                `;
            } else {
                warningContainer.innerHTML = `
                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                        ✅ Kendaraan dan driver cocok untuk mengantarkan total muatan ini.
                    </div>
                `;
            }
        }
    }

    dateInput.addEventListener('change', function() {
        // Reload page dengan query parameter date agar shipment-shipment yang tampil sesuai tanggal baru
        window.location.search = `?trip_date=${dateInput.value}`;
    });

    driverSelect.addEventListener('change', validateCapacity);
    checkboxes.forEach(cb => {
        cb.addEventListener('change', validateCapacity);
    });

    updateDrivers();
});
</script>
@endsection