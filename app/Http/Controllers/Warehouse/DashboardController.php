<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Stock;
use App\Models\GoodsReceipt;
use App\Models\Shipment;
use App\Models\StockOpname;
use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $warehouseId = auth()->user()->warehouse_id;

        // Fallback jika staff gudang tidak memiliki warehouse_id yang di-assign
        if (!$warehouseId) {
            $totalSku = Product::where('is_active', true)->count();
            $lowStockCount = 0;
            $barangMasukToday = 0;
            $siapKirimCount = 0;
            $receivingCount = 0;
            $putawayCount = 0;
            $pickingCount = 0;
            $packingCount = 0;

            return view('warehouse.dashboard', compact(
                'totalSku', 'lowStockCount', 'barangMasukToday', 'siapKirimCount',
                'receivingCount', 'putawayCount', 'pickingCount', 'packingCount'
            ));
        }

        $today = now()->toDateString();

        // 1. Total SKU aktif
        $totalSku = Product::where('is_active', true)->count();

        // 2. Stok Rendah di gudang ini
        $lowStockCount = Stock::where('warehouse_id', $warehouseId)
            ->whereHas('product', function ($query) {
                $query->where('minimum_stock', '>', 0)
                      ->whereColumn('stocks.qty', '<=', 'products.minimum_stock');
            })
            ->count();

        // 3. Barang Masuk (total kuantitas hari ini)
        $barangMasukToday = (float) GoodsReceipt::where('warehouse_id', $warehouseId)
            ->whereDate('receipt_date', $today)
            ->sum('qty');

        // 4. Siap Kirim (shipment pending / assigned di gudang ini)
        $siapKirimCount = Shipment::where('warehouse_id', $warehouseId)
            ->whereIn('status', ['pending', 'assigned'])
            ->count();

        // 5. Receiving (transaksi receipt hari ini)
        $receivingCount = GoodsReceipt::where('warehouse_id', $warehouseId)
            ->whereDate('receipt_date', $today)
            ->count();

        // 6. Putaway (transaksi opname hari ini)
        $putawayCount = StockOpname::where('warehouse_id', $warehouseId)
            ->whereDate('opname_date', $today)
            ->count();

        // 7. Picking (transaksi order masuk hari ini)
        $pickingCount = Order::where('warehouse_id', $warehouseId)
            ->whereDate('order_date', $today)
            ->count();

        // 8. Packing (transaksi shipment dibuat hari ini)
        $packingCount = Shipment::where('warehouse_id', $warehouseId)
            ->whereDate('shipment_date', $today)
            ->count();

        return view('warehouse.dashboard', compact(
            'totalSku',
            'lowStockCount',
            'barangMasukToday',
            'siapKirimCount',
            'receivingCount',
            'putawayCount',
            'pickingCount',
            'packingCount'
        ));
    }
}