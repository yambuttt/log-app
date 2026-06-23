<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Aplikasi Internal' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
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

        @keyframes softFloat {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-6px);
            }
        }

        .animate-fade-up {
            animation: fadeUp .7s ease-out both;
        }

        .animate-soft-float {
            animation: softFloat 5s ease-in-out infinite;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.72);
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
        <div class="absolute -top-20 -left-20 h-72 w-72 rounded-full bg-cyan-400/15 blur-3xl"></div>
        <div class="absolute top-0 right-0 h-80 w-80 rounded-full bg-indigo-400/15 blur-3xl"></div>
        <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-fuchsia-400/10 blur-3xl"></div>

        <div class="relative z-10 flex min-h-screen">
            <aside class="hidden w-72 shrink-0 border-r border-white/40 bg-slate-950 text-white lg:flex lg:flex-col">
                <div class="border-b border-white/10 px-6 py-6">
                    <div class="flex items-center gap-4">
                        <div
                            class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 shadow-lg backdrop-blur">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-white" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 7.5 12 3l9 4.5M4.5 9.75V16.5L12 21l7.5-4.5V9.75M12 12l9-4.5M12 12 3 7.5" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-300/80">
                                Internal System
                            </p>
                            <h1 class="mt-1 text-lg font-bold text-white">
                                Admin Panel
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-4 py-6">
                    <p class="mb-3 px-3 text-xs font-semibold uppercase tracking-[0.22em] text-slate-400">
                        Main Menu
                    </p>

                    <nav class="space-y-2">
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">🏠</span>
                            <span>Dashboard</span>
                        </a>

                        <a href="{{ route('admin.products.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium text-slate-300 transition hover:bg-white/10 hover:text-white">
                            <span class="text-lg">📦</span>
                            <span>Master Barang</span>
                        </a>

                        <a href="{{ route('admin.stocks.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.stocks.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">📊</span>
                            <span>Stok Barang</span>
                        </a>

                        <a href="{{ route('admin.goods-receipts.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.goods-receipts.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">📥</span>
                            <span>Barang Masuk</span>
                        </a>

                        <a href="{{ route('admin.inventory-movements.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.inventory-movements.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">📈</span>
                            <span>Inventory Movement</span>
                        </a>

                        <a href="{{ route('admin.wastes.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.wastes.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">🗑️</span>
                            <span>Waste</span>
                        </a>

                        <a href="{{ route('admin.stock-opnames.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.stock-opnames.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">📋</span>
                            <span>Stock Opname</span>
                        </a>

                        <a href="{{ route('admin.vehicles.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.vehicles.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">🚚</span>
                            <span>Master Kendaraan</span>
                        </a>

                        <a href="{{ route('admin.vehicle-capacities.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.vehicle-capacities.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">📦</span>
                            <span>Kapasitas Kendaraan</span>
                        </a>

                        <a href="{{ route('admin.delivery-trips.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.delivery-trips.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">🛣️</span>
                            <span>Delivery Trips</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                            <span class="text-lg">👤</span>
                            <span>Manajemen User</span>
                        </a>


                    </nav>
                </div>

                <div class="border-t border-white/10 p-4">
                    <div class="rounded-2xl bg-white/5 p-4 backdrop-blur">
                        <p class="text-sm font-semibold text-white">
                            {{ auth()->user()->name ?? 'Administrator' }}
                        </p>
                        <p class="mt-1 text-xs text-slate-400">
                            {{ auth()->user()->email ?? '-' }}
                        </p>

                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:bg-red-700">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <div class="flex min-h-screen flex-1 flex-col">
                <header class="sticky top-0 z-20 border-b border-white/40 bg-white/70 backdrop-blur-xl">
                    <div class="mx-auto flex max-w-full items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-3">
                            <button type="button" @click="sidebarOpen = true"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-lg lg:hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                                </svg>
                            </button>

                            <div>
                                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">
                                    Internal System
                                </p>
                                <h2 class="text-base font-bold text-slate-900 sm:text-lg">
                                    {{ $headerTitle ?? 'Dashboard Admin' }}
                                </h2>
                            </div>
                        </div>

                        <div class="hidden items-center gap-3 sm:flex">
                            <div class="text-right">
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ auth()->user()->name ?? 'Administrator' }}
                                </p>
                                <p class="text-xs text-slate-500">{{ auth()->user()->email ?? '-' }}</p>
                            </div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 transition hover:-translate-y-0.5 hover:bg-red-700">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    @yield('content')
                </main>
            </div>
        </div>

        <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-slate-950/50 lg:hidden"
            @click="sidebarOpen = false" style="display: none;"></div>

        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-slate-950 text-white shadow-2xl lg:hidden"
            style="display: none;">
            <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-cyan-300/80">
                        Internal System
                    </p>
                    <h2 class="mt-1 text-lg font-bold">Admin Panel</h2>
                </div>

                <button type="button" @click="sidebarOpen = false"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/10">
                    ✕
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-4 py-6">
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('dashboard') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">🏠</span>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.products.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">📦</span>
                        <span>Master Barang</span>
                    </a>

                    <a href="{{ route('admin.stocks.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.stocks.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">📊</span>
                        <span>Stok Barang</span>
                    </a>

                    <a href="{{ route('admin.goods-receipts.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.goods-receipts.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">📥</span>
                        <span>Barang Masuk</span>
                    </a>

                    <a href="{{ route('admin.inventory-movements.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.inventory-movements.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">📈</span>
                        <span>Inventory Movement</span>
                    </a>

                    <a href="{{ route('admin.wastes.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.wastes.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">🗑️</span>
                        <span>Waste</span>
                    </a>

                    <a href="{{ route('admin.stock-opnames.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.stock-opnames.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">📋</span>
                        <span>Stock Opname</span>
                    </a>

                    <a href="{{ route('admin.vehicles.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.vehicles.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">🚚</span>
                        <span>Master Kendaraan</span>
                    </a>

                    <a href="{{ route('admin.vehicle-capacities.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.vehicle-capacities.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">📦</span>
                        <span>Kapasitas Kendaraan</span>
                    </a>

                    <a href="{{ route('admin.delivery-trips.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.delivery-trips.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">🛣️</span>
                        <span>Delivery Trips</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-medium transition {{ request()->routeIs('admin.users.*') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-300 hover:bg-white/10 hover:text-white' }}">
                        <span class="text-lg">👤</span>
                        <span>Manajemen User</span>
                    </a>


                </nav>
            </div>
        </aside>
    </div>
</body>

</html>