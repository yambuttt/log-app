@extends('layouts.guest')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-slate-950">
    <div class="absolute inset-0 bg-grid"></div>

    <div class="absolute -left-16 top-16 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl animate-float-slow animate-pulse-glow"></div>
    <div class="absolute right-0 top-0 h-80 w-80 rounded-full bg-indigo-500/20 blur-3xl animate-float-medium animate-pulse-glow"></div>
    <div class="absolute bottom-0 left-1/3 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl animate-float-slow"></div>

    <div class="relative z-10 flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="grid w-full max-w-6xl overflow-hidden rounded-[28px] border border-white/10 bg-white/5 shadow-2xl shadow-black/30 lg:grid-cols-2">
            <div class="relative hidden min-h-[680px] overflow-hidden lg:block">
                <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 via-indigo-500/20 to-fuchsia-500/20"></div>
                <div class="absolute inset-0 bg-slate-950/40"></div>

                <div class="relative flex h-full flex-col justify-between p-10 xl:p-14">
                    <div class="animate-fade-soft">
                        <div class="inline-flex items-center gap-3 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-sm text-white/90 backdrop-blur">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-400"></span>
                            Sistem Internal Perusahaan
                        </div>
                    </div>

                    <div class="max-w-xl animate-fade-up">
                        <p class="mb-4 text-sm font-medium uppercase tracking-[0.28em] text-cyan-200/90">
                            Inventory & Delivery Management
                        </p>

                        <h1 class="text-4xl font-bold leading-tight text-white xl:text-5xl">
                            Kelola operasional gudang dan pengiriman dalam satu sistem.
                        </h1>

                        <p class="mt-6 max-w-lg text-base leading-7 text-slate-200/85 xl:text-lg">
                            Akses data stok, pesanan, dan proses distribusi dengan tampilan yang cepat, modern, dan nyaman digunakan di berbagai perangkat.
                        </p>

                        <div class="mt-10 grid grid-cols-2 gap-4">
                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                                <p class="text-2xl font-bold text-white">Realtime</p>
                                <p class="mt-1 text-sm text-slate-200/80">Monitoring aktivitas operasional secara langsung.</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-white/10 p-4 backdrop-blur">
                                <p class="text-2xl font-bold text-white">Responsive</p>
                                <p class="mt-1 text-sm text-slate-200/80">Nyaman dipakai dari desktop, tablet, dan mobile.</p>
                            </div>
                        </div>
                    </div>

                    <div class="animate-fade-soft">
                        <p class="text-sm text-slate-300/80">
                            © {{ date('Y') }} Perusahaan Internal System
                        </p>
                    </div>
                </div>
            </div>

            <div class="glass-card flex min-h-[680px] items-center justify-center bg-white/80 px-5 py-8 sm:px-8 lg:px-10 xl:px-14">
                <div class="w-full max-w-md animate-fade-up">
                    <div class="mb-8">
                        <div class="mb-5 inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-900 text-white shadow-lg shadow-slate-900/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5M4.5 9.75V16.5L12 21l7.5-4.5V9.75M12 12l9-4.5M12 12 3 7.5" />
                            </svg>
                        </div>

                        <h2 class="text-3xl font-bold tracking-tight text-slate-900">
                            Selamat datang
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Silakan login untuk mengakses sistem internal perusahaan.
                        </p>
                    </div>

                    <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                                Email
                            </label>
                            <div class="group relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition group-focus-within:text-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5A2.25 2.25 0 0 1 19.5 19.5h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75m19.5 0-8.69 5.793a1.875 1.875 0 0 1-2.12 0L2.25 6.75" />
                                    </svg>
                                </span>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    autofocus
                                    autocomplete="email"
                                    placeholder="Masukkan email"
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 pl-12 pr-4 text-sm text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                                >
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                                Password
                            </label>
                            <div class="group relative">
                                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition group-focus-within:text-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.875a4.5 4.5 0 1 0-9 0V10.5m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21h-10.5A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z" />
                                    </svg>
                                </span>

                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    class="w-full rounded-2xl border border-slate-200 bg-white py-3.5 pl-12 pr-14 text-sm text-slate-900 shadow-sm outline-none transition duration-200 placeholder:text-slate-400 focus:border-slate-900 focus:ring-4 focus:ring-slate-200"
                                >

                                <button
                                    type="button"
                                    id="togglePassword"
                                    class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 transition hover:text-slate-700"
                                >
                                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.644C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.21.07.434 0 .644C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>

                                    <svg id="eyeClose" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.477 10.484A3 3 0 0 0 13.5 13.5m4.242 1.742A9.955 9.955 0 0 1 12 19.5c-4.638 0-8.573-3.007-9.964-7.178a1.012 1.012 0 0 1 0-.644 10.027 10.027 0 0 1 4.512-5.669m3.17-1.32A9.956 9.956 0 0 1 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.21.07.434 0 .644a10.012 10.012 0 0 1-1.537 2.92" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between gap-3">
                            <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400"
                                >
                                <span>Ingat saya</span>
                            </label>
                        </div>

                        <button
                            type="submit"
                            class="group inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-slate-900 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/20 transition duration-300 hover:-translate-y-0.5 hover:bg-slate-800"
                        >
                            <span>Masuk ke Sistem</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition duration-300 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.75 21 12m0 0-3.75 3.25M21 12H3" />
                            </svg>
                        </button>
                    </form>

                    <div class="mt-8 border-t border-slate-200 pt-6">
                        <p class="text-center text-xs leading-5 text-slate-500 sm:text-sm">
                            Gunakan akun yang telah diberikan administrator perusahaan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClose = document.getElementById('eyeClose');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';

            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            eyeOpen.classList.toggle('hidden', isPassword);
            eyeClose.classList.toggle('hidden', !isPassword);
        });
    }
</script>
@endsection