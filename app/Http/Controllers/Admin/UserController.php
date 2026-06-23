<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DriverProfile;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with(['warehouse', 'driverProfile'])
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        $warehouses = Warehouse::where('is_active', true)->orderBy('name')->get();

        return view('admin.users.create', compact('warehouses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:admin,warehouse,driver'],
            'warehouse_id' => ['nullable', 'exists:warehouses,id'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'license_number' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
            'role.required' => 'Role wajib dipilih.',
        ]);

        if ($validated['role'] === 'warehouse' && empty($validated['warehouse_id'])) {
            return back()
                ->withErrors(['warehouse_id' => 'User gudang wajib memilih gudang.'])
                ->withInput();
        }

        if ($validated['role'] !== 'warehouse') {
            $validated['warehouse_id'] = null;
        }

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $validated['role'],
                'warehouse_id' => $validated['warehouse_id'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'is_active' => $request->boolean('is_active'),
            ]);

            if ($validated['role'] === 'driver') {
                DriverProfile::create([
                    'user_id' => $user->id,
                    'license_number' => $validated['license_number'] ?? null,
                    'address' => $validated['address'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }
}