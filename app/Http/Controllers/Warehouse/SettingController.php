<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $warehouse = auth()->user()->warehouse;

        abort_if(! $warehouse, 404);

        return view('warehouse.settings.location', compact('warehouse'));
    }

    public function update(Request $request): RedirectResponse
    {
        $warehouse = auth()->user()->warehouse;

        abort_if(! $warehouse, 404);

        $validated = $request->validate([
            'address' => ['required', 'string'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ], [
            'address.required' => 'Alamat gudang wajib diisi.',
            'latitude.required' => 'Latitude gudang wajib diisi.',
            'longitude.required' => 'Longitude gudang wajib diisi.',
        ]);

        $warehouse->update($validated);

        return redirect()
            ->route('warehouse.settings.location.edit')
            ->with('success', 'Lokasi gudang berhasil diperbarui.');
    }
}