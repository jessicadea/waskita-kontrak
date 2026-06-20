<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Waskita Precast</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50">

<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- LEFT PANEL --}}
    <div class="flex items-center justify-center px-8 py-12">
        <div class="w-full max-w-xl">

            {{-- LOGO --}}
            <div class="flex items-center gap-3 mb-10">
                <img src="{{ asset('images/logo waskita.png') }}"
                        alt="Waskita Precast"
                        class="h-14 w-auto object-contain">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">Waskita Beton Precast</h1>
                    <p class="text-sm text-gray-500">Contract Management System</p>
                </div>
            </div>

            {{-- TITLE --}}
            <h2 class="text-4xl font-bold text-slate-900 mb-3">
                Buat akun baru
            </h2>

            <p class="text-gray-500 mb-8">
                Daftar sebagai client untuk mulai melakukan pemesanan produk.
            </p>

            {{-- REGISTER FORM --}}
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- NAME --}}
                <div>
                    <label class="font-semibold text-slate-900">
                        Nama lengkap / perusahaan
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           autocomplete="name"
                           placeholder="PT Karya Mandiri"
                           class="mt-2 w-full rounded-xl border-slate-200 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="font-semibold text-slate-900">
                        Email perusahaan
                    </label>

                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="username"
                           placeholder="nama@perusahaan.co.id"
                           class="mt-2 w-full rounded-xl border-slate-200 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div>
                    <label class="font-semibold text-slate-900">
                        Kata sandi
                    </label>

                    <input type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="mt-2 w-full rounded-xl border-slate-200 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PASSWORD CONFIRMATION --}}
                <div>
                    <label class="font-semibold text-slate-900">
                        Konfirmasi kata sandi
                    </label>

                    <input type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           placeholder="••••••••"
                           class="mt-2 w-full rounded-xl border-slate-200 bg-white px-4 py-3 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- BUTTON --}}
                <button type="submit"
                        class="w-full bg-slate-900 hover:bg-slate-800 text-white py-4 rounded-xl font-bold shadow-sm transition">
                    Daftar ke Sistem
                </button>

                {{-- LOGIN LINK --}}
                <p class="text-center text-sm text-gray-500">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                       class="font-semibold text-blue-600 hover:underline">
                        Masuk sekarang
                    </a>
                </p>
            </form>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="hidden lg:flex relative overflow-hidden bg-gradient-to-br from-slate-950 via-blue-950 to-orange-700 text-white items-center">

        {{-- Background Pattern --}}
        <div class="absolute inset-0 opacity-20"
             style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 22px 22px;">
        </div>

        <div class="relative z-10 max-w-2xl mx-auto px-14">


            {{-- Heading --}}
            <h2 class="text-4xl font-bold leading-tight">
                Mulai pemesanan produk precast dengan monitoring project real-time.
            </h2>

            {{-- Description --}}
            <p class="text-blue-100 mt-6 text-lg leading-relaxed">
                Ajukan pesanan, unggah dokumen pendukung, pantau tahapan produksi,
                dan unduh laporan project secara digital.
            </p>

            {{-- Illustration --}}
            <div class="mt-10 rounded-3xl bg-white/10 border border-white/20 p-6 backdrop-blur shadow-2xl">
                <div class="bg-white rounded-2xl p-10 flex items-center justify-center">
                    <img src="{{ asset('images/login-illustration.jpg') }}"
                         alt="Waskita Beton Precast Illustration"
                         class="max-h-80 w-auto object-contain">
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>