<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('unit')
            ->latest()
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $units = Unit::orderBy('name')->get();

        return view('admin.products.create', compact('units'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku'],
            'unit_id' => ['required', 'exists:units,id'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'harga_modal' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama barang wajib diisi.',
            'sku.required' => 'SKU wajib diisi.',
            'sku.unique' => 'SKU sudah dipakai.',
            'unit_id.required' => 'Satuan wajib dipilih.',
            'unit_id.exists' => 'Satuan tidak valid.',
            'weight_kg.numeric' => 'Berat harus berupa angka.',
            'harga_modal.required' => 'Harga modal wajib diisi.',
            'harga_modal.numeric' => 'Harga modal harus berupa angka.',
            'harga_modal.min' => 'Harga modal tidak boleh kurang dari 0.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
            'minimum_stock.numeric' => 'Minimum stok harus berupa angka.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['minimum_stock'] = $request->input('minimum_stock') !== null ? $request->input('minimum_stock') : 10;

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        $units = Unit::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'units'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:products,sku,' . $product->id],
            'unit_id' => ['required', 'exists:units,id'],
            'weight_kg' => ['nullable', 'numeric', 'min:0'],
            'harga_modal' => ['required', 'numeric', 'min:0'],
            'harga_jual' => ['required', 'numeric', 'min:0'],
            'minimum_stock' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama barang wajib diisi.',
            'sku.required' => 'SKU wajib diisi.',
            'sku.unique' => 'SKU sudah dipakai.',
            'unit_id.required' => 'Satuan wajib dipilih.',
            'unit_id.exists' => 'Satuan tidak valid.',
            'weight_kg.numeric' => 'Berat harus berupa angka.',
            'harga_modal.required' => 'Harga modal wajib diisi.',
            'harga_modal.numeric' => 'Harga modal harus berupa angka.',
            'harga_modal.min' => 'Harga modal tidak boleh kurang dari 0.',
            'harga_jual.required' => 'Harga jual wajib diisi.',
            'harga_jual.numeric' => 'Harga jual harus berupa angka.',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0.',
            'minimum_stock.numeric' => 'Minimum stok harus berupa angka.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['minimum_stock'] = $request->input('minimum_stock') !== null ? $request->input('minimum_stock') : 10;

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Barang berhasil diperbarui.');
    }
}