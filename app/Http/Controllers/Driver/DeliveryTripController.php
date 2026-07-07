<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\DeliveryTrip;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DeliveryTripController extends Controller
{
    public function index(): View
    {
        $trips = DeliveryTrip::with([
                'vehicle',
                'tripShipments.shipment.order',
                'tripShipments.shipment.items.product',
            ])
            ->where('driver_user_id', auth()->id())
            ->whereIn('status', ['planned', 'on_trip', 'returning'])
            ->latest()
            ->paginate(10);

        return view('driver.delivery-trips.index', compact('trips'));
    }

    public function start(DeliveryTrip $deliveryTrip): RedirectResponse
    {
        abort_unless($deliveryTrip->driver_user_id === auth()->id(), 403);

        $deliveryTrip->update([
            'status' => 'on_trip',
        ]);

        // Update all shipments in this trip to 'on_delivery'
        foreach ($deliveryTrip->shipments as $shipment) {
            $shipment->update([
                'status' => 'on_delivery',
            ]);
        }

        auth()->user()->update([
            'availability_status' => 'on_delivery',
        ]);

        return back()->with('success', 'Trip dimulai.');
    }

    public function completeStop(DeliveryTrip $deliveryTrip, int $tripShipmentId): RedirectResponse
    {
        abort_unless($deliveryTrip->driver_user_id === auth()->id(), 403);

        $tripShipment = $deliveryTrip->tripShipments()
            ->with('shipment.order')
            ->findOrFail($tripShipmentId);

        $tripShipment->shipment->update([
            'status' => 'completed',
        ]);

        // Update order terkait ke completed jika semua shipment-nya selesai
        $order = $tripShipment->shipment->order;
        if ($order) {
            $order->update(['status' => 'completed']);
        }

        return back()->with('success', 'Stop berhasil diselesaikan.');
    }

    public function returnHome(DeliveryTrip $deliveryTrip): RedirectResponse
    {
        abort_unless($deliveryTrip->driver_user_id === auth()->id(), 403);
        abort_unless($deliveryTrip->status === 'on_trip', 403);

        // Pastikan semua stop sudah selesai sebelum return home
        $hasIncompleteStop = $deliveryTrip->shipments()
            ->where('status', '!=', 'completed')
            ->exists();

        if ($hasIncompleteStop) {
            return back()->with('error', 'Semua stop harus diselesaikan terlebih dahulu sebelum return home.');
        }

        $deliveryTrip->update([
            'status' => 'returning',
        ]);

        auth()->user()->update([
            'availability_status' => 'returning',
        ]);

        return back()->with('success', 'Status diubah ke Return Home. Selamat perjalanan pulang!');
    }

    public function finish(DeliveryTrip $deliveryTrip): RedirectResponse
    {
        abort_unless($deliveryTrip->driver_user_id === auth()->id(), 403);

        $deliveryTrip->update([
            'status' => 'completed',
        ]);

        // Pastikan semua shipment di trip ini selesai
        $shipments = $deliveryTrip->shipments()->get();
        foreach ($shipments as $shipment) {
            if ($shipment->status !== 'completed') {
                $shipment->update(['status' => 'completed']);
            }
            // Update order terkait ke completed
            if ($shipment->order) {
                $shipment->order->update(['status' => 'completed']);
            }
        }

        auth()->user()->update([
            'availability_status' => 'available',
        ]);

        return back()->with('success', 'Trip selesai. Selamat datang kembali!');
    }
}