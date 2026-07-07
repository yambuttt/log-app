<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverVehicleAssignment;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleMaintenanceController extends Controller
{
    public function index(): View
    {
        // Kendaraan aktif yang bisa dimasukkan ke maintenance
        $availableVehicles = Vehicle::where('status', 'active')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Kendaraan yang sedang di-maintenance
        $maintenanceVehicles = Vehicle::where('status', 'maintenance')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('admin.vehicle-maintenances.index', compact('availableVehicles', 'maintenanceVehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'maintenance_notes' => ['nullable', 'string'],
        ], [
            'vehicle_id.required' => 'Kendaraan wajib dipilih.',
            'vehicle_id.exists' => 'Kendaraan tidak valid.',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);

        $vehicle->update([
            'status' => 'maintenance',
            'is_active' => false,
            'maintenance_notes' => $validated['maintenance_notes'] ?? null,
        ]);

        // Cari assignment driver untuk hari ini atau hari ke depan yang menggunakan kendaraan ini
        $today = now()->toDateString();
        $assignments = DriverVehicleAssignment::with('driver')
            ->where('vehicle_id', $vehicle->id)
            ->where('assignment_date', '>=', $today)
            ->get();

        foreach ($assignments as $assignment) {
            if ($assignment->driver) {
                // Update driver status back to available
                $assignment->driver->update([
                    'availability_status' => 'available',
                ]);
            }
            $assignment->delete();
        }

        return redirect()
            ->route('admin.vehicle-maintenances.index')
            ->with('success', 'Kendaraan berhasil dimasukkan ke daftar maintenance.');
    }

    public function release(Vehicle $vehicle): RedirectResponse
    {
        $vehicle->update([
            'status' => 'active',
            'is_active' => true,
            'maintenance_notes' => null,
        ]);

        return redirect()
            ->route('admin.vehicle-maintenances.index')
            ->with('success', 'Kendaraan berhasil diaktifkan kembali.');
    }
}
