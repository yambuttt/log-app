<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Services\AssignWaitingShipmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ShipmentController extends Controller
{
    public function index(): View
    {
        $shipments = Shipment::with(['order.items.product', 'vehicle'])
            ->where('driver_user_id', auth()->id())
            ->whereIn('status', ['assigned', 'on_delivery', 'delivered', 'returning'])
            ->whereDoesntHave('tripShipments')
            ->latest()
            ->paginate(10);

        return view('driver.shipments.index', compact('shipments'));
    }

    public function start(Shipment $shipment): RedirectResponse
    {
        abort_unless($shipment->driver_user_id === auth()->id(), 403);

        $shipment->update([
            'status' => 'on_delivery',
        ]);

        auth()->user()->update([
            'availability_status' => 'on_delivery',
        ]);

        return back()->with('success', 'Pengiriman dimulai.');
    }

    public function delivered(Shipment $shipment): RedirectResponse
    {
        abort_unless($shipment->driver_user_id === auth()->id(), 403);

        $shipment->update([
            'status' => 'delivered',
        ]);

        auth()->user()->update([
            'availability_status' => 'returning',
        ]);

        return back()->with('success', 'Pengiriman selesai, silakan kembali ke gudang.');
    }

    public function returned(Shipment $shipment, AssignWaitingShipmentService $assignWaitingShipmentService): RedirectResponse
    {
        abort_unless($shipment->driver_user_id === auth()->id(), 403);

        $shipment->update([
            'status' => 'completed',
        ]);

        auth()->user()->update([
            'availability_status' => 'available',
        ]);

        $newShipment = $assignWaitingShipmentService->assignToDriver(
            auth()->user(),
            now()->toDateString()
        );

        return back()->with(
            'success',
            $newShipment
                ? 'Kembali ke gudang berhasil. Shipment menunggu otomatis dilimpahkan ke Anda.'
                : 'Kembali ke gudang berhasil. Saat ini belum ada shipment menunggu yang cocok.'
        );
    }
}