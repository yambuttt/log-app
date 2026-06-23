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
        $todayAssignment = DriverVehicleAssignment::with('vehicle')
            ->where('driver_user_id', auth()->id())
            ->where('assignment_date', now()->toDateString())
            ->first();

        $activeShipments = Shipment::with(['order', 'vehicle'])
            ->where('driver_user_id', auth()->id())
            ->whereIn('status', ['assigned', 'on_delivery', 'delivered', 'returning'])
            ->latest()
            ->take(5)
            ->get();

        return view('driver.dashboard', compact('todayAssignment', 'activeShipments'));
    }
}