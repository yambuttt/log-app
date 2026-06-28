<?php

namespace App\Http\Controllers;

use App\Models\DeliveryTrip;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShipmentPrintController extends Controller
{
    /**
     * Print a single shipment's Surat Jalan.
     */
    public function print(Shipment $shipment): View
    {
        $shipment->load([
            'order.items.product',
            'items.product.unit',
            'driver',
            'vehicle',
            'warehouse'
        ]);

        $shipments = collect([$shipment]);

        return view('shipments.print-surat-jalan', compact('shipments'));
    }

    /**
     * Print all Surat Jalans in a delivery trip.
     */
    public function printAll(DeliveryTrip $deliveryTrip): View
    {
        $shipments = $deliveryTrip->shipments()
            ->with([
                'order.items.product',
                'items.product.unit',
                'driver',
                'vehicle',
                'warehouse'
            ])
            ->get();

        return view('shipments.print-surat-jalan', compact('shipments', 'deliveryTrip'));
    }
}
