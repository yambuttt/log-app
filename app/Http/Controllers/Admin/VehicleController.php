<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\DriverVehicleAssignment;
use App\Models\VehicleMaintenanceLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(): View
    {
        $vehicles = Vehicle::with(['activeTrip.driver', 'todayAssignment.driver'])->latest()->paginate(10);

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create(): View
    {
        return view('admin.vehicles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'plate_number' => ['required', 'string', 'max:255', 'unique:vehicles,plate_number'],
            'vehicle_type' => ['required', 'in:small,large'],
            'fuel_efficiency' => ['required', 'numeric', 'min:0.1'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama kendaraan wajib diisi.',
            'plate_number.required' => 'Nomor plat wajib diisi.',
            'plate_number.unique' => 'Nomor plat sudah digunakan.',
            'vehicle_type.required' => 'Jenis kendaraan wajib dipilih.',
            'fuel_efficiency.required' => 'Konsumsi bensin wajib diisi.',
            'fuel_efficiency.numeric' => 'Konsumsi bensin harus berupa angka.',
            'fuel_efficiency.min' => 'Konsumsi bensin minimal 0.1 km/l.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['status'] = 'active';

        Vehicle::create($validated);

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle): View
    {
        $vehicle->load('maintenanceLogs');
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'plate_number' => ['required', 'string', 'max:255', 'unique:vehicles,plate_number,' . $vehicle->id],
            'vehicle_type' => ['required', 'in:small,large'],
            'fuel_efficiency' => ['required', 'numeric', 'min:0.1'],
            'is_active' => ['nullable', 'boolean'],
            'maintenance_notes' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama kendaraan wajib diisi.',
            'plate_number.required' => 'Nomor plat wajib diisi.',
            'plate_number.unique' => 'Nomor plat sudah digunakan.',
            'vehicle_type.required' => 'Jenis kendaraan wajib dipilih.',
            'fuel_efficiency.required' => 'Konsumsi bensin wajib diisi.',
            'fuel_efficiency.numeric' => 'Konsumsi bensin harus berupa angka.',
            'fuel_efficiency.min' => 'Konsumsi bensin minimal 0.1 km/l.',
        ]);

        $inMaintenance = $request->boolean('in_maintenance');
        $previousStatus = $vehicle->status;

        if ($inMaintenance) {
            // Jika masuk ke status maintenance
            $validated['status'] = 'maintenance';
            $validated['is_active'] = false;
            $validated['maintenance_notes'] = $request->input('maintenance_notes');

            if ($previousStatus !== 'maintenance') {
                // Log perbaikan baru
                VehicleMaintenanceLog::create([
                    'vehicle_id' => $vehicle->id,
                    'start_date' => now(),
                    'notes' => $request->input('maintenance_notes'),
                ]);

                // Hapus penugasan sopir hari ini/masa depan menggunakan kendaraan ini
                $today = now()->toDateString();
                $assignments = DriverVehicleAssignment::with('driver')
                    ->where('vehicle_id', $vehicle->id)
                    ->where('assignment_date', '>=', $today)
                    ->get();

                foreach ($assignments as $assignment) {
                    if ($assignment->driver) {
                        $assignment->driver->update([
                            'availability_status' => 'available',
                        ]);
                    }
                    $assignment->delete();
                }
            } else {
                // Hanya update catatan log perbaikan yang sedang berjalan
                VehicleMaintenanceLog::where('vehicle_id', $vehicle->id)
                    ->whereNull('end_date')
                    ->update([
                        'notes' => $request->input('maintenance_notes'),
                    ]);
            }
        } else {
            // Jika status active / dilepas dari maintenance
            $validated['status'] = 'active';
            $validated['is_active'] = $request->boolean('is_active');
            $validated['maintenance_notes'] = null;

            if ($previousStatus === 'maintenance') {
                // Tutup log perbaikan yang terbuka
                VehicleMaintenanceLog::where('vehicle_id', $vehicle->id)
                    ->whereNull('end_date')
                    ->update([
                        'end_date' => now(),
                    ]);
            }
        }

        $vehicle->update($validated);

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }
}