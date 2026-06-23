<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        $stocks = Stock::with(['warehouse', 'product.unit'])
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('admin.stocks.index', compact('stocks'));
    }
}