<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold">Dashboard Admin</h2>
            <p class="text-sm text-gray-500 mt-1">
                Ringkasan analytics pesanan, project, dan peak season pemesanan.
            </p>
        </div>
    </x-slot>

    <div class="space-y-6">

        <div class="bg-gradient-to-r from-blue-950 to-blue-800 rounded-2xl p-6 text-white shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div>
                    <p class="text-blue-100 text-sm">Selamat datang kembali,</p>
                    <h3 class="text-2xl font-black mt-1">{{ auth()->user()->name }}</h3>
                    <p class="text-blue-100 mt-2 max-w-2xl">
                        Pantau tren pemesanan, peak season, produk terlaris, verifikasi order, dan monitoring project melalui dashboard admin.
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

        {{-- FILTER PERIODE --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Filter Periode Analytics</h3>
                    <p class="text-sm text-gray-500">
                        Digunakan untuk melihat tren pemesanan dan peak season berdasarkan rentang tanggal tertentu.
                    </p>
                </div>

                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold w-fit">
                    {{ $periodLabel }}
                </span>
            </div>

            <form method="GET" action="/admin/dashboard" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="text-sm font-semibold text-slate-700">Tanggal Awal</label>
                    <input type="date"
                           name="start_date"
                           value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Tanggal Akhir</label>
                    <input type="date"
                           name="end_date"
                           value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                           class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div class="flex gap-3">
                    <button type="submit"
                            class="w-full px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                        Terapkan
                    </button>

                    <a href="/admin/dashboard"
                       class="w-full text-center px-5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- STATS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-gray-500 text-sm font-medium">Total Order</p>
                <h3 class="text-3xl font-black mt-2 text-slate-900">{{ $totalOrders }}</h3>
                <p class="text-xs text-gray-400 mt-4">Total pesanan pada periode terpilih.</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-green-600 text-sm font-medium">Approved</p>
                <h3 class="text-3xl font-black text-green-600 mt-2">{{ $approved }}</h3>
                <p class="text-xs text-gray-400 mt-4">Pesanan yang telah disetujui.</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-yellow-600 text-sm font-medium">Pending</p>
                <h3 class="text-3xl font-black text-yellow-600 mt-2">{{ $pending }}</h3>
                <p class="text-xs text-gray-400 mt-4">Pesanan menunggu verifikasi.</p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <p class="text-orange-600 text-sm font-medium">Percepatan Produksi</p>
                <h3 class="text-3xl font-black text-orange-600 mt-2">{{ $acceleratedOrders }}</h3>
                <p class="text-xs text-gray-400 mt-4">Order yang mengajukan percepatan.</p>
            </div>
        </div>

        {{-- PEAK SEASON --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-sm">
                <p class="text-orange-100 text-sm font-semibold">Peak Season Pemesanan</p>
                <h3 class="text-3xl font-black mt-3">{{ $peakSeasonLabel }}</h3>
                <p class="mt-3 text-orange-50">
                    Periode dengan pemesanan tertinggi sebanyak
                    <span class="font-black">{{ $peakSeasonTotal }}</span> order.
                </p>
                <div class="mt-4 bg-white/15 rounded-xl p-3 text-sm text-orange-50">
                    <p class="font-bold">Rekomendasi:</p>
                    <p>
                        Siapkan material dan kapasitas produksi minimal 30 hari sebelum
                        {{ $peakSeasonLabel }} untuk mengantisipasi lonjakan pemesanan.
                    </p>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <p class="text-gray-500 text-sm font-semibold">Produk Terlaris</p>
                <h3 class="text-2xl font-black mt-3 text-slate-900">{{ $topProductName }}</h3>
                <p class="mt-3 text-sm text-gray-500">
                    Total quantity pada periode ini:
                    <span class="font-bold text-slate-900">{{ number_format($topProductQty) }}</span> unit.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <p class="text-gray-500 text-sm font-semibold">Total Client</p>
                <h3 class="text-2xl font-black mt-3 text-slate-900">{{ $totalClients }}</h3>
                <p class="mt-3 text-sm text-gray-500">
                    Jumlah akun client yang terdaftar pada sistem.
                </p>
            </div>
        </div>

        {{-- CHARTS --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="mb-4">
                    <h3 class="font-bold text-lg text-slate-900">Tren Pemesanan</h3>
                    <p class="text-sm text-gray-500">
                        Grafik untuk mengetahui peak season pemesanan berdasarkan bulan.
                    </p>
                </div>

                <div class="h-72">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="mb-4">
                    <h3 class="font-bold text-lg text-slate-900">Status Order</h3>
                    <p class="text-sm text-gray-500">Distribusi status verifikasi pesanan.</p>
                </div>

                <div class="h-72 flex items-center justify-center">
                    <canvas id="orderChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="mb-4">
                    <h3 class="font-bold text-lg text-slate-900">Status Project</h3>
                    <p class="text-sm text-gray-500">Ringkasan status project berjalan.</p>
                </div>

                <div class="h-72">
                    <canvas id="projectChart"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                <div class="mb-4">
                    <h3 class="font-bold text-lg text-slate-900">Top Produk Berdasarkan Quantity</h3>
                    <p class="text-sm text-gray-500">Produk yang paling banyak dipesan pada periode terpilih.</p>
                </div>

                <div class="h-72">
                    <canvas id="productChart"></canvas>
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
                                    <p class="font-semibold text-slate-900">{{ $order->user->name ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->company_name ?? '-' }}</p>
                                </td>

                                <td class="px-5 py-4">
                                    @if($order->items->count() > 0)
                                        <p class="font-semibold text-slate-900">{{ $order->items->count() }} Produk</p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->items->first()->product->product_name ?? '-' }}
                                            @if($order->items->count() > 1)
                                                + {{ $order->items->count() - 1 }} lainnya
                                            @endif
                                        </p>
                                    @else
                                        <p class="font-semibold text-slate-900">{{ $order->product->product_name ?? '-' }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->variant->type_name ?? '-' }}</p>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-900">{{ $order->project_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->project_location ?? '-' }}</p>
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
        const trendLabels = @json($trendLabels);
        const trendData = @json($trendData);

        new Chart(document.getElementById('trendChart'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Jumlah Order',
                    data: trendData,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointRadius: 4,
                    pointBackgroundColor: '#2563EB'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { color: '#E2E8F0' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

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
                        labels: { usePointStyle: true, boxWidth: 8 }
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
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { color: '#E2E8F0' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });

        new Chart(document.getElementById('productChart'), {
            type: 'bar',
            data: {
                labels: @json($topProducts->pluck('product_name')),
                datasets: [{
                    label: 'Quantity',
                    data: @json($topProducts->pluck('total_quantity')),
                    backgroundColor: '#0F172A',
                    borderRadius: 10
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: { precision: 0 },
                        grid: { color: '#E2E8F0' }
                    },
                    y: { grid: { display: false } }
                }
            }
        });
    </script>
</x-app-layout>