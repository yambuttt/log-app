<!DOCTYPE html>
<html lang="id">
@stack('styles')

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Dashboard Gudang' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes fadeUp {
            0% {
                opacity: 0;
                transform: translateY(18px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-up {
            animation: fadeUp .7s ease-out both;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .bg-grid-light {
            background-image:
                linear-gradient(to right, rgba(15, 23, 42, 0.04) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(15, 23, 42, 0.04) 1px, transparent 1px);
            background-size: 28px 28px;
        }
    </style>
</head>

<body class="min-h-screen bg-slate-100 text-slate-800">
    <div x-data="{ sidebarOpen: false }" class="relative min-h-screen overflow-hidden">
        <div class="absolute inset-0 bg-grid-light"></div>
        <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-emerald-400/15 blur-3xl"></div>
        <div class="absolute top-0 right-0 h-80 w-80 rounded-full bg-lime-400/15 blur-3xl"></div>

        <div class="relative z-10 flex min-h-screen">
            <aside class="hidden w-72 shrink-0 border-r border-white/40 bg-emerald-950 text-white lg:flex lg:flex-col">
                <div class="border-b border-white/10 px-6 py-6">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 backdrop-blur">
                            <span class="text-2xl">📦</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-300/80">Warehouse
                                System</p>
                            <h1 class="mt-1 text-lg font-bold text-white">Panel Gudang</h1>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-6">
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">Menu Gudang
                    </p>

                    <nav class="space-y-2">
                        <a href="{{ route('warehouse.dashboard') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.dashboard') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>🏠</span><span>Dashboard</span>
                        </a>
                        <a href="{{ route('warehouse.stocks.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.stocks.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>📦</span><span>Stok Barang</span>
                        </a>
                        <a href="{{ route('warehouse.inventory-movements.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.inventory-movements.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>📈</span><span>Inventory Movement</span>
                        </a>
                        <a href="{{ route('warehouse.goods-receipts.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.goods-receipts.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>📥</span><span>Barang Masuk</span>
                        </a>
                        <a href="{{ route('warehouse.wastes.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.wastes.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>🗑️</span><span>Waste</span>
                        </a>
                        <a href="{{ route('warehouse.stock-opnames.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.stock-opnames.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>📋</span><span>Stock Opname</span>
                        </a>
                        <a href="{{ route('warehouse.orders.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.orders.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>🧾</span><span>Pesanan</span>
                        </a>
                        <a href="{{ route('warehouse.delivery-trips.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.delivery-trips.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>🛣️</span><span>Delivery Trips</span>
                        </a>
                        <a href="{{ route('warehouse.shipments.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.shipments.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>🚚</span><span>Pengiriman</span>
                        </a>
                        <a href="{{ route('warehouse.settings.location.edit') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.settings.location.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span>📍</span><span>Lokasi Gudang</span>
                        </a>
                    </nav>
                </div>

                <div class="border-t border-white/10 p-4">
                    <div class="rounded-2xl bg-white/5 p-4 backdrop-blur">
                        <p class="text-sm font-semibold text-white">{{ auth()->user()->name ?? 'User Gudang' }}</p>
                        <p class="mt-1 text-xs text-slate-400">{{ auth()->user()->email ?? '-' }}</p>

                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex min-h-screen flex-1 flex-col">
                <header class="sticky top-0 z-20 border-b border-white/40 bg-white/70 backdrop-blur-xl">
                    <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button type="button" @click="sidebarOpen = true"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-900 text-white lg:hidden">
                                ☰
                            </button>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Warehouse
                                    System</p>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">
                                    {{ $headerTitle ?? 'Dashboard Gudang' }}
                                </h2>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @yield('content')
                </main>
            </div>
        </div>

        <div x-show="sidebarOpen" x-cloak x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/50 lg:hidden"
            @click="sidebarOpen = false"></div>

        <aside x-show="sidebarOpen" x-cloak x-transition:enter="transition transform ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition transform ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-emerald-950 text-white shadow-2xl lg:hidden">
            <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-emerald-300/80">Warehouse System
                    </p>
                    <h2 class="mt-1 text-lg font-bold">Panel Gudang</h2>
                </div>
                <button type="button" @click="sidebarOpen = false"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10">✕</button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-6">
                <nav class="space-y-2">
                    <a href="{{ route('warehouse.dashboard') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.dashboard') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>🏠</span><span>Dashboard</span>
                    </a>
                    <a href="{{ route('warehouse.stocks.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.stocks.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>📦</span><span>Stok Barang</span>
                    </a>
                    <a href="{{ route('warehouse.inventory-movements.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.inventory-movements.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>📈</span><span>Inventory Movement</span>
                    </a>
                    <a href="{{ route('warehouse.goods-receipts.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.goods-receipts.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>📥</span><span>Barang Masuk</span>
                    </a>
                    <a href="{{ route('warehouse.wastes.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.wastes.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>🗑️</span><span>Waste</span>
                    </a>
                    <a href="{{ route('warehouse.stock-opnames.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.stock-opnames.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>📋</span><span>Stock Opname</span>
                    </a>
                    <a href="{{ route('warehouse.orders.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.orders.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>🧾</span><span>Pesanan</span>
                    </a>
                    <a href="{{ route('warehouse.delivery-trips.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.delivery-trips.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>🛣️</span><span>Delivery Trips</span>
                    </a>
                    <a href="{{ route('warehouse.shipments.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.shipments.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>🚚</span><span>Pengiriman</span>
                    </a>
                    <a href="{{ route('warehouse.settings.location.edit') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('warehouse.settings.location.*') ? 'bg-white text-emerald-950 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span>📍</span><span>Lokasi Gudang</span>
                    </a>

                </nav>
            </div>
        </aside>
    </div>
    @stack('scripts')
</body>

</html>