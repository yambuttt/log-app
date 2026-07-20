<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalesController extends Controller
{
    public function index(Request $request): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        abort_if(! $warehouseId, 403, 'Anda tidak ditugaskan di gudang mana pun.');

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $query = Order::with(['items.product.unit', 'warehouse'])
            ->where('warehouse_id', $warehouseId)
            ->where('status', 'completed')
            ->whereBetween('order_date', [$startDate, $endDate]);

        $orders = $query->latest('order_date')->get();

        // Calculate aggregates
        $totalPenjualan = 0;
        $totalModal = 0;

        foreach ($orders as $order) {
            $orderPenjualan = 0;
            $orderModal = 0;

            foreach ($order->items as $item) {
                $qty = (float) $item->qty;
                $jual = (float) ($item->product->harga_jual ?? 0);
                $modal = (float) ($item->product->harga_modal ?? 0);

                $orderPenjualan += $qty * $jual;
                $orderModal += $qty * $modal;
            }

            $order->total_penjualan = $orderPenjualan;
            $order->total_modal = $orderModal;
            $order->keuntungan = $orderPenjualan - $orderModal;
            $order->margin = $orderPenjualan > 0 ? ($order->keuntungan / $orderPenjualan) * 100 : 0;

            $totalPenjualan += $orderPenjualan;
            $totalModal += $orderModal;
        }

        $totalKeuntungan = $totalPenjualan - $totalModal;
        $marginRataRata = $totalPenjualan > 0 ? ($totalKeuntungan / $totalPenjualan) * 100 : 0;

        return view('warehouse.sales.index', compact(
            'orders',
            'startDate',
            'endDate',
            'totalPenjualan',
            'totalModal',
            'totalKeuntungan',
            'marginRataRata'
        ));
    }
}
