<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Waskita Monitoring Kontrak') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-50 text-gray-900">
    <div x-data="{ sidebarOpen: false }" class="min-h-screen flex">

        {{-- MOBILE OVERLAY --}}
        <div x-show="sidebarOpen"
             x-transition.opacity
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-black/40 z-30 md:hidden">
        </div>

        {{-- SIDEBAR --}}
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed md:static z-40 inset-y-0 left-0 w-72 bg-[#0B1324] text-white transform md:translate-x-0 transition-transform duration-300 flex flex-col shadow-2xl">

            {{-- BRAND --}}
            <div class="h-24 px-6 flex items-center border-b border-white/10">
                <div>
                    <h1 class="text-xl font-black tracking-tight">Waskita Precast</h1>
                    <p class="text-xs text-slate-400 mt-1">Contract Monitoring System</p>
                </div>
            </div>

            {{-- USER MINI --}}
            <div class="px-6 py-5 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center font-black shadow-lg shadow-blue-900/30">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold leading-tight truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
            </div>

            @php
                $role = auth()->user()->role;

                $menuClass = function ($active = false) {
                    return $active
                        ? 'flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold transition bg-blue-600 text-white shadow-lg shadow-blue-950/30'
                        : 'flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-semibold transition text-slate-300 hover:bg-white/10 hover:text-white';
                };

                $iconClass = function ($active = false) {
                    return $active
                        ? 'w-5 h-5 text-white'
                        : 'w-5 h-5 text-slate-400 group-hover:text-white';
                };
            @endphp

            {{-- MENU --}}
            <nav class="flex-1 px-4 py-5 space-y-1.5 overflow-y-auto">

                {{-- DASHBOARD --}}
                <a href="/{{ $role }}/dashboard"
                   class="group {{ $menuClass(request()->is($role.'/dashboard')) }}">
                    <svg class="{{ $iconClass(request()->is($role.'/dashboard')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 13h7V4H4v9zm9 7h7V4h-7v16zM4 20h7v-5H4v5z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                @if($role === 'client')
                    <a href="/client/orders/create"
                       class="group {{ $menuClass(request()->is('client/orders/create')) }}">
                        <svg class="{{ $iconClass(request()->is('client/orders/create')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7.5l-8-4.5-8 4.5m16 0l-8 4.5m8-4.5v9l-8 4.5m0-9l-8-4.5m8 4.5v9m-8-13.5v9l8 4.5" />
                        </svg>
                        <span>Pemesanan</span>
                    </a>

                    <a href="/client/orders"
                       class="group {{ $menuClass(request()->is('client/orders') || request()->is('client/orders/*')) }}">
                        <svg class="{{ $iconClass(request()->is('client/orders') || request()->is('client/orders/*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                        <span>Daftar Order</span>
                    </a>

                    <a href="/client/kontrak"
                       class="group {{ $menuClass(request()->is('client/kontrak*')) }}">
                        <svg class="{{ $iconClass(request()->is('client/kontrak*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 3v5h5M9 13h6M9 17h4" />
                        </svg>
                        <span>Kontrak</span>
                    </a>

                    <a href="/client/orders"
                       class="group {{ $menuClass(request()->is('client/projects/*/monitoring')) }}">
                        <svg class="{{ $iconClass(request()->is('client/projects/*/monitoring')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 19V9m7 10V5m7 14v-7M4 19h16" />
                        </svg>
                        <span>Monitoring Project</span>
                    </a>

                    <a href="/client/orders"
                       class="group {{ $menuClass(request()->is('client/laporan*')) }}">
                        <svg class="{{ $iconClass(request()->is('client/laporan*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h10a2 2 0 012 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 012-2z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 8h6M9 12h6M9 16h4" />
                        </svg>
                        <span>Laporan</span>
                    </a>
                @endif

                @if($role === 'admin')
                    <a href="/admin/orders"
                       class="group {{ $menuClass(request()->is('admin/orders*')) }}">
                        <svg class="{{ $iconClass(request()->is('admin/orders*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 7h18M5 7l1.5 12h11L19 7M9 11h6" />
                        </svg>
                        <span>Pesanan Masuk</span>
                    </a>

                    <a href="/admin/projects"
                       class="group {{ $menuClass(request()->is('admin/projects*')) }}">
                        <svg class="{{ $iconClass(request()->is('admin/projects*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 20h16M6 20V9l6-5 6 5v11M9 20v-6h6v6" />
                        </svg>
                        <span>Project</span>
                    </a>

                    <a href="/admin/project-board"
                       class="group {{ $menuClass(request()->is('admin/project-board')) }}">
                        <svg class="{{ $iconClass(request()->is('admin/project-board')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 19V9m7 10V5m7 14v-7M4 19h16" />
                        </svg>
                        <span>Monitoring Project</span>
                    </a>

                    <a href="/admin/employees"
                       class="group {{ $menuClass(request()->is('admin/employees*')) }}">
                        <svg class="{{ $iconClass(request()->is('admin/employees*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11a4 4 0 10-8 0m8 0a4 4 0 01-8 0m8 0v1a4 4 0 01-8 0v-1M5 21a7 7 0 0114 0" />
                        </svg>
                        <span>Pegawai</span>
                    </a>
                @endif

                @if($role === 'pegawai')
                    <a href="/pegawai/projects"
                       class="group {{ $menuClass(request()->is('pegawai/projects*')) }}">
                        <svg class="{{ $iconClass(request()->is('pegawai/projects*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5h6M9 9h6M9 13h4M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                        <span>Tugas Project</span>
                    </a>

                    <a href="/pegawai/work-updates"
                       class="group {{ $menuClass(request()->is('pegawai/work-updates*')) }}">
                        <svg class="{{ $iconClass(request()->is('pegawai/work-updates*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 20h9M16.5 3.5a2.1 2.1 0 013 3L7 19l-4 1 1-4L16.5 3.5z" />
                        </svg>
                        <span>Update Pekerjaan</span>
                    </a>
                @endif

                @if($role === 'pimpinan')
                    <a href="/pimpinan/work-updates"
                       class="group {{ $menuClass(request()->is('pimpinan/work-updates*')) }}">
                        <svg class="{{ $iconClass(request()->is('pimpinan/work-updates*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7 3h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                        <span>Validasi Progress</span>
                    </a>

                    <a href="/pimpinan/projects"
                       class="group {{ $menuClass(request()->is('pimpinan/projects*')) }}">
                        <svg class="{{ $iconClass(request()->is('pimpinan/projects*')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 19V9m7 10V5m7 14v-7M4 19h16" />
                        </svg>
                        <span>Monitoring Project</span>
                    </a>
                @endif

                <div class="pt-4 mt-4 border-t border-white/10">
                    <a href="{{ route('profile.edit') }}"
                       class="group {{ $menuClass(request()->routeIs('profile.edit')) }}">
                        <svg class="{{ $iconClass(request()->routeIs('profile.edit')) }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10.3 4.3l.4-1.8h2.6l.4 1.8a7.8 7.8 0 012.1.9l1.6-1 1.8 1.8-1 1.6c.4.7.7 1.4.9 2.1l1.8.4v2.6l-1.8.4a7.8 7.8 0 01-.9 2.1l1 1.6-1.8 1.8-1.6-1a7.8 7.8 0 01-2.1.9l-.4 1.8h-2.6l-.4-1.8a7.8 7.8 0 01-2.1-.9l-1.6 1-1.8-1.8 1-1.6a7.8 7.8 0 01-.9-2.1l-1.8-.4v-2.6l1.8-.4c.2-.7.5-1.4.9-2.1l-1-1.6 1.8-1.8 1.6 1c.7-.4 1.4-.7 2.1-.9z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
                        </svg>
                        <span>Profile</span>
                    </a>
                </div>
            </nav>

            {{-- LOGOUT --}}
            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-500/10 hover:bg-red-500 text-red-300 hover:text-white transition px-4 py-3 rounded-2xl text-sm font-bold">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12H3m0 0l4-4m-4 4l4 4M10 4h8a2 2 0 012 2v12a2 2 0 01-2 2h-8" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN AREA --}}
        <div class="flex-1 min-w-0 flex flex-col">

            {{-- TOPBAR --}}
            <header class="h-20 bg-white border-b border-slate-200 px-4 md:px-8 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center gap-4">
                    <button type="button" @click="sidebarOpen = true"
                            class="md:hidden w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <div>
                        @isset($header)
                            {{ $header }}
                        @else
                            <h2 class="text-xl font-bold text-gray-900">Dashboard</h2>
                        @endisset
                        <p class="text-sm text-gray-500 mt-1">
                            Sistem Pencatatan dan Monitoring Kontrak Pemesanan Produk
                        </p>
                    </div>
                </div>

                <div class="hidden lg:flex items-center gap-4">
                    <div class="relative">
                        <input type="text"
                               placeholder="Cari data..."
                               class="w-72 rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <button type="button" class="relative w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center">
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-3 pl-4 border-l">
                        <div class="text-right">
                            <p class="text-sm font-bold">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- CONTENT --}}
            <main class="flex-1 p-4 md:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>