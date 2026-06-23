<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryTrip;
use Illuminate\View\View;

class DeliveryTripController extends Controller
{
    public function index(): View
    {
        $trips = DeliveryTrip::with(['warehouse', 'driver', 'vehicle', 'shipments.order'])
            ->latest()
            ->paginate(10);

        return view('admin.delivery-trips.index', compact('trips'));
    }
}
