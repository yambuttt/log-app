<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverVehicleAssignment;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleAssignmentController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();

        $todayAssignment = DriverVehicleAssignment::with('vehicle')
            ->where('driver_user_id', auth()->id())
            ->where('assignment_date', $today)
            ->first();

        // Cari kendaraan yang sudah dipilih driver lain hari ini
        $assignedVehicleIds = DriverVehicleAssignment::where('assignment_date', $today)
            ->where('driver_user_id', '!=', auth()->id())
            ->pluck('vehicle_id');

        $vehicles = Vehicle::where('is_active', true)
            ->whereNotIn('id', $assignedVehicleIds)
            ->orderBy('name')
            ->get();

        return view('driver.vehicle-assignment.index', compact('todayAssignment', 'vehicles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'notes' => ['nullable', 'string'],
        ], [
            'vehicle_id.required' => 'Kendaraan wajib dipilih.',
            'vehicle_id.exists' => 'Kendaraan tidak valid.',
        ]);

        // Validasi double assignment jika ada driver lain yang duluan memilih kendaraan ini hari ini
        $alreadyAssigned = DriverVehicleAssignment::where('assignment_date', now()->toDateString())
            ->where('vehicle_id', $validated['vehicle_id'])
            ->where('driver_user_id', '!=', auth()->id())
            ->exists();

        if ($alreadyAssigned) {
            return back()
                ->withErrors(['vehicle_id' => 'Kendaraan ini sudah dipilih oleh driver lain hari ini.'])
                ->withInput();
        }

        DriverVehicleAssignment::updateOrCreate(
            [
                'driver_user_id' => auth()->id(),
                'assignment_date' => now()->toDateString(),
            ],
            [
                'vehicle_id' => $validated['vehicle_id'],
                'notes' => $validated['notes'] ?? null,
            ]
        );

        return redirect()
            ->route('driver.vehicle-assignment.index')
            ->with('success', 'Kendaraan hari ini berhasil dipilih.');
    }
}