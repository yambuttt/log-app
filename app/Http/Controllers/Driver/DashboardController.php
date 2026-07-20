<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverVehicleAssignment;
use App\Models\Shipment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();
        $driverId = auth()->id();

        $todayAssignment = DriverVehicleAssignment::with('vehicle')
            ->where('driver_user_id', $driverId)
            ->where('assignment_date', $today)
            ->first();

        // Active / recent shipments for the list
        $activeShipments = Shipment::with(['order', 'vehicle'])
            ->where('driver_user_id', $driverId)
            ->whereIn('status', ['assigned', 'on_delivery', 'delivered', 'completed', 'returning'])
            ->latest()
            ->take(5)
            ->get();

        // Operational stats
        $tugasHariIni = Shipment::where('driver_user_id', $driverId)
            ->where(function ($query) use ($today) {
                $query->whereDate('shipment_date', $today)
                      ->orWhereIn('status', ['assigned', 'on_delivery', 'returning']);
            })
            ->count();

        $dalamPerjalananCount = Shipment::where('driver_user_id', $driverId)
            ->where('status', 'on_delivery')
            ->count();

        $terkirimCount = Shipment::where('driver_user_id', $driverId)
            ->where('status', 'completed')
            ->whereDate('updated_at', $today)
            ->count();

        $pendingCount = Shipment::where('driver_user_id', $driverId)
            ->whereIn('status', ['pending', 'assigned'])
            ->count();

        return view('driver.dashboard', compact(
            'todayAssignment',
            'activeShipments',
            'tugasHariIni',
            'dalamPerjalananCount',
            'terkirimCount',
            'pendingCount'
        ));
    }
}