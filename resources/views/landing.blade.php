<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waskita Beton Precast - Contract Monitoring System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white text-slate-900">

{{-- NAVBAR --}}
<header class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="/" class="flex items-center">
            <img src="{{ asset('images/logo waskita.png') }}"
                 alt="Waskita Precast"
                 class="h-12 w-auto object-contain">
        </a>

        <nav class="hidden md:flex items-center gap-9 text-sm font-bold text-slate-700">
            <a href="#produk" class="hover:text-orange-500">Produk</a>
            <a href="#layanan" class="hover:text-orange-500">Layanan</a>
            <a href="#monitoring" class="hover:text-orange-500">Monitoring</a>
            <a href="#tentang" class="hover:text-orange-500">Tentang Kami</a>
        </nav>

        <div class="flex items-center gap-4">
            <a href="/login" class="text-sm font-bold text-blue-900 hover:text-orange-500">
                Login
            </a>

            <a href="/register"
               class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-sm">
                Pesan Sekarang
            </a>
        </div>
    </div>
</header>

{{-- HERO --}}
<section class="relative pt-28 overflow-hidden bg-gradient-to-br from-white via-blue-50 to-slate-100">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center min-h-[650px]">

        <div class="relative z-10">
            <p class="text-orange-500 font-bold mb-4">
                Contract Monitoring System
            </p>

            <h1 class="text-4xl lg:text-6xl font-black leading-tight text-blue-950">
                Monitoring Kontrak Pemesanan Produk
                <span class="text-orange-500">Precast Beton</span>
            </h1>

            <p class="text-slate-600 text-lg leading-relaxed mt-6 max-w-xl">
                Sistem digital untuk pemesanan produk beton precast, verifikasi kontrak,
                monitoring progress project, dokumentasi pekerjaan, dan laporan otomatis.
            </p>

            <div class="flex flex-wrap gap-4 mt-9">
                <a href="/register"
                   class="bg-orange-500 hover:bg-orange-600 text-white px-7 py-4 rounded-xl font-bold shadow-md">
                    Mulai Pemesanan
                </a>

                <a href="#layanan"
                   class="border border-blue-900 text-blue-900 hover:bg-blue-900 hover:text-white px-7 py-4 rounded-xl font-bold">
                    Pelajari Sistem
                </a>
            </div>
        </div>

        <div class="relative">
            <div class="absolute inset-0 bg-blue-200/40 blur-3xl rounded-full"></div>

            <img src="{{ asset('images/waskita.jpg') }}"
                 alt="Waskita Beton Precast"
                 class="relative w-full h-[480px] object-cover rounded-[2rem] shadow-2xl">
        </div>
    </div>
</section>

{{-- FEATURE BAR --}}
<section class="relative -mt-14 z-20">
    <div class="max-w-7xl mx-auto px-6">
        <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 px-8 py-8 grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">

            <div class="flex items-start gap-5 xl:border-r border-slate-200 xl:pr-8">
                <div class="shrink-0 w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7.5l-8-4.5-8 4.5m16 0l-8 4.5m8-4.5v9l-8 4.5m0-9l-8-4.5m8 4.5v9m-8-13.5v9l8 4.5" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-black text-blue-950">
                        Pemesanan Digital
                    </h3>

                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Proses pemesanan produk precast menjadi lebih cepat dan mudah.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-5 xl:border-r border-slate-200 xl:pr-8">
                <div class="shrink-0 w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M14 3v5h5M9 13l2 2 4-4" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-black text-blue-950">
                        Verifikasi Kontrak
                    </h3>

                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Verifikasi kontrak secara digital, transparan, dan terstruktur.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-5 xl:border-r border-slate-200 xl:pr-8">
                <div class="shrink-0 w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 19V9m7 10V5m7 14v-7" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 19h16" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-black text-blue-950">
                        Monitoring Project
                    </h3>

                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Pantau progress proyek secara real-time dan akurat.
                    </p>
                </div>
            </div>

            <div class="flex items-start gap-5">
                <div class="shrink-0 w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3h10a2 2 0 012 2v16l-3-2-3 2-3-2-3 2V5a2 2 0 012-2z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 8h6M9 12h6M9 16h4" />
                    </svg>
                </div>

                <div>
                    <h3 class="text-lg font-black text-blue-950">
                        Laporan Otomatis
                    </h3>

                    <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                        Laporan pekerjaan dan dokumen tersedia otomatis dalam PDF.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- PRODUK --}}
<section id="produk" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <p class="text-orange-500 font-bold mb-2">PRODUK KAMI</p>
            <h2 class="text-4xl font-black text-blue-950">
                Produk Precast Berkualitas
            </h2>
            <p class="text-slate-500 mt-4">
                Berbagai produk beton precast untuk mendukung kebutuhan pembangunan infrastruktur.
            </p>
        </div>

        @php
            $products = [
                ['name' => 'Spun Pile', 'desc' => 'Tiang pancang beton', 'image' => 'spun-pile.jpg'],
                ['name' => 'PC-I Girder', 'desc' => 'Girder jembatan pracetak', 'image' => 'pci-girder.jpg'],
                ['name' => 'Box Culvert', 'desc' => 'Saluran beton bertulang', 'image' => 'box-culvert.jpg'],
                ['name' => 'U-Ditch', 'desc' => 'Saluran U-Ditch beton', 'image' => 'pcu-girder.jpg'],
                ['name' => 'Road Barrier', 'desc' => 'Pembatas jalan beton', 'image' => 'road-barrier.jpg'],
                ['name' => 'Square Pile', 'desc' => 'Tiang pancang persegi', 'image' => 'square-pile.jpg'],
            ];
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-7">
            @foreach($products as $product)
                <div class="group bg-white rounded-2xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-xl hover:-translate-y-1 transition">
                    <img src="{{ asset('images/'.$product['image']) }}"
                         alt="{{ $product['name'] }}"
                         class="w-full h-56 object-cover group-hover:scale-105 transition duration-500">

                    <div class="p-6">
                        <h3 class="text-xl font-black text-blue-950">
                            {{ $product['name'] }}
                        </h3>

                        <p class="text-slate-500 mt-2">
                            {{ $product['desc'] }}
                        </p>

                        <a href="/register"
                           class="inline-flex mt-5 font-bold text-orange-500 hover:text-orange-600">
                            Pesan Produk →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- LAYANAN --}}
<section id="layanan" class="py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="text-center max-w-3xl mx-auto mb-14">
            <p class="text-orange-500 font-bold mb-2">LAYANAN DIGITAL</p>
            <h2 class="text-4xl font-black text-blue-950">
                Alur Sistem Terintegrasi
            </h2>
            <p class="text-slate-500 mt-4">
                Sistem mendukung proses pemesanan hingga monitoring project dalam satu platform.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            @php
                $steps = [
                    ['no' => '01', 'title' => 'Pemesanan', 'desc' => 'Client memilih produk, volume, quantity, dan tanggal kirim.'],
                    ['no' => '02', 'title' => 'Verifikasi', 'desc' => 'Admin melakukan verifikasi pesanan dan dokumen pendukung.'],
                    ['no' => '03', 'title' => 'Project', 'desc' => 'Pesanan yang disetujui dikonversi menjadi project.'],
                    ['no' => '04', 'title' => 'Monitoring', 'desc' => 'Progress pekerjaan diperbarui dan dipantau secara digital.'],
                ];
            @endphp

            @foreach($steps as $step)
                <div class="bg-white rounded-2xl p-7 border border-slate-200 shadow-sm">
                    <p class="text-orange-500 font-black text-sm">{{ $step['no'] }}</p>
                    <h3 class="text-xl font-black text-blue-950 mt-3">
                        {{ $step['title'] }}
                    </h3>
                    <p class="text-slate-500 mt-3 leading-relaxed">
                        {{ $step['desc'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- MONITORING --}}
<section id="monitoring" class="py-24 bg-blue-950 text-white">
    <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
        <div>
            <p class="text-orange-400 font-bold mb-3">REAL-TIME MONITORING</p>

            <h2 class="text-4xl lg:text-5xl font-black leading-tight">
                Pantau Progress Project Secara Lebih Terstruktur
            </h2>

            <p class="text-blue-100 mt-6 leading-relaxed">
                Sistem membantu admin, pegawai, pimpinan, dan client dalam memantau status
                project, validasi progress, dokumentasi pekerjaan, serta laporan penyelesaian.
            </p>

            <div class="grid grid-cols-2 gap-5 mt-8">
                <div class="bg-white/10 border border-white/10 rounded-2xl p-5">
                    <h3 class="text-3xl font-black text-orange-400">4</h3>
                    <p class="text-sm text-blue-100 mt-1">Role Pengguna</p>
                </div>

                <div class="bg-white/10 border border-white/10 rounded-2xl p-5">
                    <h3 class="text-3xl font-black text-orange-400">100%</h3>
                    <p class="text-sm text-blue-100 mt-1">Progress Project</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2rem] p-6 text-slate-900 shadow-2xl">
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-slate-50 rounded-2xl p-4 min-h-72">
                    <h4 class="font-bold mb-4">To Do</h4>
                    <div class="bg-white rounded-xl p-4 shadow-sm border">
                        <p class="font-bold">Order Baru</p>
                        <p class="text-xs text-gray-500 mt-1">Pending</p>
                    </div>
                </div>

                <div class="bg-orange-50 rounded-2xl p-4 min-h-72">
                    <h4 class="font-bold mb-4 text-orange-700">In Progress</h4>
                    <div class="bg-white rounded-xl p-4 shadow-sm border">
                        <p class="font-bold">Produksi</p>
                        <p class="text-xs text-gray-500 mt-1">60%</p>
                    </div>
                </div>

                <div class="bg-green-50 rounded-2xl p-4 min-h-72">
                    <h4 class="font-bold mb-4 text-green-700">Done</h4>
                    <div class="bg-white rounded-xl p-4 shadow-sm border">
                        <p class="font-bold">Selesai</p>
                        <p class="text-xs text-gray-500 mt-1">100%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TENTANG --}}
<section id="tentang" class="py-24 bg-white">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <p class="text-orange-500 font-bold mb-2">TENTANG SISTEM</p>
        <h2 class="text-4xl font-black text-blue-950">
            Sistem Pencatatan dan Monitoring Kontrak Pemesanan Produk
        </h2>

        <p class="text-slate-500 mt-5 leading-relaxed">
            Sistem ini dikembangkan untuk membantu proses pemesanan produk precast beton,
            verifikasi pesanan, pengelolaan kontrak, pembentukan project, penugasan pegawai,
            monitoring progress pekerjaan, dan penyusunan laporan project secara digital.
        </p>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-6xl mx-auto px-6">
        <div class="rounded-[2rem] bg-gradient-to-r from-orange-500 to-blue-700 text-white p-10 md:p-16 text-center shadow-2xl">
            <h2 class="text-4xl font-black">
                Siap Memulai Pemesanan Produk?
            </h2>

            <p class="text-orange-50 mt-4 max-w-2xl mx-auto">
                Buat akun client dan ajukan order produk precast dengan monitoring progress project secara digital.
            </p>

            <div class="flex flex-wrap justify-center gap-4 mt-8">
                <a href="/register" class="bg-white text-blue-950 px-8 py-4 rounded-xl font-bold hover:bg-slate-100">
                    Daftar Sekarang
                </a>

                <a href="/login" class="bg-white/10 border border-white/30 px-8 py-4 rounded-xl font-bold hover:bg-white/20">
                    Login
                </a>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-blue-950 text-white py-10">
    <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between gap-6">
        <div>
            <h3 class="font-bold text-lg">Waskita Beton Precast</h3>
            <p class="text-sm text-blue-200 mt-1">Contract Monitoring System</p>
        </div>

        <p class="text-sm text-blue-200">
            © {{ date('Y') }} Waskita Beton Precast. All rights reserved.
        </p>
    </div>
</footer>

</body>
</html>