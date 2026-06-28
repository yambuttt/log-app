<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $lowStocks = Stock::with(['product.unit', 'warehouse'])
            ->whereHas('product', function ($query) {
                $query->where('minimum_stock', '>', 0)
                      ->whereColumn('stocks.qty', '<=', 'products.minimum_stock');
            })
            ->get();

        $lowStockCount = $lowStocks->count();
        $totalProducts = Product::count();
        $ordersTodayCount = Order::whereDate('order_date', now()->toDateString())->count();
        $activeDriversCount = User::where('role', 'driver')
            ->where('availability_status', 'on_delivery')
            ->count();

        return view('admin.dashboard', compact(
            'lowStocks',
            'lowStockCount',
            'totalProducts',
            'ordersTodayCount',
            'activeDriversCount'
        ));
    }
}