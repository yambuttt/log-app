<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
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

        Vehicle::create($validated);

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle): View
    {
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

        $vehicle->update($validated);

        return redirect()
            ->route('admin.vehicles.index')
            ->with('success', 'Kendaraan berhasil diperbarui.');
    }
}