<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold">Dashboard Admin</h2>
            <p class="text-sm text-gray-500 mt-1">
                Ringkasan pesanan, project, dan aktivitas terbaru pada sistem.
            </p>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- WELCOME --}}
        <div class="bg-gradient-to-r from-blue-950 to-blue-800 rounded-2xl p-6 text-white shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <p class="text-blue-100 text-sm">Selamat datang kembali,</p>
                    <h3 class="text-2xl font-black mt-1">{{ auth()->user()->name }}</h3>
                    <p class="text-blue-100 mt-2 max-w-2xl">
                        Pantau pesanan masuk, verifikasi order client, kelola kontrak, dan monitoring progress project melalui dashboard admin.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="/admin/orders"
                       class="bg-white text-blue-950 px-5 py-3 rounded-xl text-sm font-bold hover:bg-slate-100">
                        Kelola Order
                    </a>
                    <a href="/admin/projects"
                       class="bg-orange-500 text-white px-5 py-3 rounded-xl text-sm font-bold hover:bg-orange-600">
                        Lihat Project
                    </a>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Order</p>
                        <h3 class="text-3xl font-black mt-2 text-slate-900">{{ $totalOrders }}</h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6M7 3h7l5 5v13H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4">Seluruh pesanan client yang masuk ke sistem.</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-yellow-600 text-sm font-medium">Pending</p>
                        <h3 class="text-3xl font-black text-yellow-600 mt-2">{{ $pending }}</h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-yellow-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4">Pesanan yang masih menunggu verifikasi admin.</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-green-600 text-sm font-medium">Approved</p>
                        <h3 class="text-3xl font-black text-green-600 mt-2">{{ $approved }}</h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4">Pesanan yang telah disetujui dan dapat diproses.</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-red-600 text-sm font-medium">Rejected</p>
                        <h3 class="text-3xl font-black text-red-600 mt-2">{{ $rejected }}</h3>
                    </div>

                    <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-4">Pesanan yang ditolak berdasarkan hasil verifikasi.</p>
            </div>

        </div>

        {{-- CHARTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Status Order</h3>
                        <p class="text-sm text-gray-500">Distribusi status verifikasi pesanan.</p>
                    </div>
                </div>

                <div class="h-72 flex items-center justify-center">
                    <canvas id="orderChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-bold text-lg text-slate-900">Status Project</h3>
                        <p class="text-sm text-gray-500">Ringkasan status project berjalan.</p>
                    </div>
                </div>

                <div class="h-72">
                    <canvas id="projectChart"></canvas>
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Order Terbaru</h3>
                    <p class="text-sm text-gray-500">Daftar pesanan terakhir dari client.</p>
                </div>

                <a href="/admin/orders" class="text-blue-600 text-sm font-bold hover:text-blue-800">
                    Kelola →
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr class="border-b border-slate-200 text-left">
                            <th class="px-5 py-4 font-semibold">Client</th>
                            <th class="px-5 py-4 font-semibold">Produk</th>
                            <th class="px-5 py-4 font-semibold">Project</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($latestOrders as $order)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">
                                        {{ $order->user->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->company_name ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">
                                        {{ $order->product->product_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->variant->type_name ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-900">
                                        {{ $order->project_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->project_location ?? '-' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    @if($order->status_verify === 'approved')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            Approved
                                        </span>
                                    @elseif($order->status_verify === 'pending')
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
                                <td colspan="4" class="text-center py-10 text-gray-500">
                                    Tidak ada data order terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        new Chart(document.getElementById('orderChart'), {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved', 'Rejected'],
                datasets: [{
                    data: [{{ $pending }}, {{ $approved }}, {{ $rejected }}],
                    backgroundColor: ['#F59E0B', '#10B981', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('projectChart'), {
            type: 'bar',
            data: {
                labels: ['Belum Mulai', 'Berjalan', 'Selesai'],
                datasets: [{
                    label: 'Jumlah Project',
                    data: [{{ $projectNotStarted }}, {{ $projectInProgress }}, {{ $projectDone }}],
                    backgroundColor: ['#94A3B8', '#2563EB', '#10B981'],
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            color: '#E2E8F0'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>