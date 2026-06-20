<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold">Dashboard Pimpinan</h2>
            <p class="text-sm text-gray-500 mt-1">
                Ringkasan validasi progress dan monitoring project.
            </p>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- HERO --}}
        <div class="bg-gradient-to-r from-blue-950 to-blue-800 text-white p-6 rounded-2xl shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <p class="text-blue-100 text-sm">Selamat datang,</p>
                    <h3 class="text-2xl font-black mt-1">{{ auth()->user()->name }}</h3>
                    <p class="text-blue-100 mt-2 max-w-2xl">
                        Pantau progress project, lakukan validasi pekerjaan, dan tinjau status penyelesaian proyek secara digital.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="/pimpinan/work-updates"
                       class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-3 rounded-xl text-sm font-bold">
                        Validasi Progress
                    </a>

                    <a href="/pimpinan/projects"
                       class="bg-white text-blue-950 hover:bg-slate-100 px-5 py-3 rounded-xl text-sm font-bold">
                        Monitoring Project
                    </a>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-yellow-600 text-sm font-medium">Pending Validasi</p>
                        <h3 class="text-3xl font-black text-yellow-600 mt-2">{{ $pendingUpdates }}</h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-4">
                    Update pekerjaan yang masih menunggu validasi pimpinan.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Approved</p>
                        <h3 class="text-3xl font-black text-green-600 mt-2">{{ $approvedUpdates }}</h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-4">
                    Update pekerjaan yang telah disetujui.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-red-600 text-sm font-medium">Rejected</p>
                        <h3 class="text-3xl font-black text-red-600 mt-2">{{ $rejectedUpdates }}</h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-4">
                    Update pekerjaan yang ditolak dan perlu diperbaiki.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-blue-600 text-sm font-medium">Project Aktif</p>
                        <h3 class="text-3xl font-black text-blue-600 mt-2">{{ $activeProjects }}</h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 19V9m7 10V5m7 14v-7M4 19h16" />
                        </svg>
                    </div>
                </div>

                <p class="text-xs text-gray-400 mt-4">
                    Project yang sedang berjalan dan membutuhkan monitoring.
                </p>
            </div>

        </div>

        {{-- MAIN CONTENT --}}
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- LATEST UPDATES --}}
            <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Update Terbaru</h3>
                        <p class="text-sm text-gray-500">
                            Update pekerjaan terakhir yang dikirim oleh pegawai.
                        </p>
                    </div>

                    <a href="/pimpinan/work-updates" class="text-blue-600 text-sm font-bold hover:text-blue-800">
                        Lihat Semua →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-50 text-slate-600">
                            <tr class="border-b border-slate-200 text-left">
                                <th class="px-5 py-4 font-semibold">Project</th>
                                <th class="px-5 py-4 font-semibold">Pegawai</th>
                                <th class="px-5 py-4 font-semibold">Progress</th>
                                <th class="px-5 py-4 font-semibold">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100">
                            @forelse($latestUpdates as $update)
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-900">
                                            {{ $update->project->project_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $update->project->project_location ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        <p class="font-medium text-slate-900">
                                            {{ $update->employee->employee_name }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-24 bg-slate-100 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full bg-blue-600"
                                                     style="width: {{ min($update->progress_percent, 100) }}%">
                                                </div>
                                            </div>

                                            <span class="font-bold text-slate-700">
                                                {{ $update->progress_percent }}%
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-5 py-4">
                                        @if($update->validation_status === 'approved')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                Approved
                                            </span>
                                        @elseif($update->validation_status === 'pending')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                                <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-12">
                                        <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-3">
                                            <svg class="w-7 h-7 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                            </svg>
                                        </div>
                                        <p class="font-semibold text-slate-900">Belum ada update pekerjaan</p>
                                        <p class="text-sm text-gray-500 mt-1">
                                            Update pekerjaan dari pegawai akan tampil pada halaman ini.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SIDE PANEL --}}
            <div class="space-y-6">

                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="font-bold text-lg text-slate-900">Aksi Cepat</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Akses halaman validasi dan monitoring project.
                    </p>

                    <div class="space-y-3 mt-5">
                        <a href="/pimpinan/work-updates"
                           class="flex items-center justify-between w-full px-4 py-3 rounded-xl bg-orange-500 text-white font-bold hover:bg-orange-600">
                            <span>Validasi Progress</span>
                            <span>→</span>
                        </a>

                        <a href="/pimpinan/projects"
                           class="flex items-center justify-between w-full px-4 py-3 rounded-xl bg-blue-50 text-blue-700 font-bold hover:bg-blue-100">
                            <span>Monitoring Project</span>
                            <span>→</span>
                        </a>
                    </div>
                </div>

                <div class="bg-blue-950 rounded-2xl p-6 text-white shadow-sm">
                    <h3 class="font-bold text-lg">Alur Validasi</h3>

                    <div class="space-y-4 mt-5 text-sm">
                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold">
                                1
                            </span>
                            <p class="text-blue-100">
                                Pegawai mengirim update progress pekerjaan.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold">
                                2
                            </span>
                            <p class="text-blue-100">
                                Pimpinan meninjau progress dan dokumentasi pekerjaan.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold">
                                3
                            </span>
                            <p class="text-blue-100">
                                Progress disetujui atau ditolak berdasarkan hasil validasi.
                            </p>
                        </div>

                        <div class="flex items-start gap-3">
                            <span class="w-7 h-7 rounded-full bg-orange-500 flex items-center justify-center text-xs font-bold">
                                4
                            </span>
                            <p class="text-blue-100">
                                Progress yang disetujui akan memengaruhi status project.
                            </p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</x-app-layout>