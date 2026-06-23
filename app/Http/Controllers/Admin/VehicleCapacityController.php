<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vehicle;
use App\Models\VehicleCapacity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleCapacityController extends Controller
{
    public function index(): View
    {
        $vehicleCapacities = VehicleCapacity::with(['vehicle', 'product'])
            ->latest()
            ->paginate(10);

        return view('admin.vehicle-capacities.index', compact('vehicleCapacities'));
    }

    public function create(): View
    {
        $vehicles = Vehicle::where('is_active', true)->orderBy('name')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.vehicle-capacities.create', compact('vehicles', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'product_id' => ['required', 'exists:products,id'],
            'max_qty' => ['required', 'integer', 'min:1'],
        ], [
            'vehicle_id.required' => 'Kendaraan wajib dipilih.',
            'product_id.required' => 'Produk wajib dipilih.',
            'max_qty.required' => 'Kapasitas maksimal wajib diisi.',
            'max_qty.integer' => 'Kapasitas maksimal harus berupa angka bulat.',
            'max_qty.min' => 'Kapasitas minimal 1.',
        ]);

        VehicleCapacity::updateOrCreate(
            [
                'vehicle_id' => $validated['vehicle_id'],
                'product_id' => $validated['product_id'],
            ],
            [
                'max_qty' => $validated['max_qty'],
            ]
        );

        return redirect()
            ->route('admin.vehicle-capacities.index')
            ->with('success', 'Kapasitas kendaraan berhasil disimpan.');
    }
}