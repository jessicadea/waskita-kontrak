<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Detail Order</h2>
        <p class="text-sm text-gray-500 mt-1">
            Verifikasi pesanan, kontrak otomatis, dan project dari order client.
        </p>
    </x-slot>

    @php
        $productionEstimates = [];
        $totalProductionDays = 0;
        $totalBasePrice = 0;
        $totalAccelerationFee = 0;
        $totalEstimatedCost = 0;

        $deliveryDate = \Carbon\Carbon::parse($order->delivery_date)->startOfDay();
        $availableDays = max(\Carbon\Carbon::today()->diffInDays($deliveryDate), 1);

        foreach ($order->items as $item) {
            $standard = \App\Models\ProductWorkStandard::where('product_id', $item->product_id)
                ->where('variant_id', $item->variant_id)
                ->where('volume_id', $item->volume_id)
                ->first();

            $capacityPerDay = $standard->capacity_per_day ?? 10;
            $basePricePerUnit = $standard->base_price_per_unit ?? 1000000;

            $productionDays = (int) ceil($item->quantity / max($capacityPerDay, 1));
            $usagePercent = (int) round(($productionDays / $availableDays) * 100);

            $basePrice = $item->quantity * $basePricePerUnit;

            $accelerationFee = 0;

            if ($productionDays > $availableDays) {
                $accelerationPercent = (int) round((($productionDays - $availableDays) / $productionDays) * 100);
                $accelerationFee = (int) round($basePrice * ($accelerationPercent / 100) * 0.5);
            }

            $totalCost = $basePrice + $accelerationFee;

            $productionEstimates[] = [
                'product' => $item->product->product_name ?? '-',
                'quantity' => $item->quantity,
                'capacity_per_day' => $capacityPerDay,
                'production_days' => $productionDays,
                'usage_percent' => $usagePercent,
                'base_price' => $basePrice,
                'acceleration_fee' => $accelerationFee,
                'total_cost' => $totalCost,
            ];

            $totalProductionDays += $productionDays;
            $totalBasePrice += $basePrice;
            $totalAccelerationFee += $accelerationFee;
            $totalEstimatedCost += $totalCost;
        }

        $totalSdm = 18;

        if ($totalProductionDays <= $availableDays * 0.7) {
            $productionStatus = 'Aman';
            $productionClass = 'bg-green-100 text-green-700 border-green-200';
        } elseif ($totalProductionDays <= $availableDays) {
            $productionStatus = 'Cukup Ketat';
            $productionClass = 'bg-yellow-100 text-yellow-700 border-yellow-200';
        } else {
            $productionStatus = 'Perlu Percepatan';
            $productionClass = 'bg-red-100 text-red-700 border-red-200';
        }
    @endphp

    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('error') }}
            </div>
        @endif

        @if($order->requires_acceleration)
            <div class="rounded-2xl border border-yellow-200 bg-yellow-50 p-5">
                <div class="flex items-start gap-3">
                    <div class="text-2xl">⚡</div>
                    <div>
                        <p class="font-bold text-yellow-800">Percepatan Produksi Diajukan</p>
                        <p class="text-sm text-yellow-700 mt-1">
                            Client mengajukan percepatan produksi dan pesanan ini memerlukan peninjauan admin sebelum disetujui.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            <div class="xl:col-span-2 space-y-6">

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <div class="flex justify-between items-start gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">
                                ORD-{{ $order->created_at->format('Y') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                            </p>

                            <h3 class="text-xl font-bold text-slate-900">
                                {{ $order->project_name }}
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                {{ $order->project_location }}
                            </p>
                        </div>

                        <div class="flex flex-col gap-2 items-end">
                            @if($order->status_verify === 'approved')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                    Disetujui
                                </span>
                            @elseif($order->status_verify === 'pending')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                    Pending
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    Ditolak
                                </span>
                            @endif

                            @if($order->requires_acceleration)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                    ⚡ Percepatan Produksi
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 text-sm">
                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Client</p>
                            <p class="font-semibold text-slate-900">{{ $order->user->name ?? '-' }}</p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Perusahaan</p>
                            <p class="font-semibold text-slate-900">{{ $order->company_name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->company_type }}</p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Tanggal Kirim</p>
                            <p class="font-semibold text-slate-900">
                                {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') }}
                            </p>
                        </div>

                        <div class="bg-slate-50 rounded-xl p-4">
                            <p class="text-gray-500">Kondisi Pengiriman</p>
                            <p class="font-semibold text-slate-900">{{ $order->delivery_cond }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-sm font-semibold text-slate-900">Daftar Produk dalam Kontrak</p>
                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-blue-50 text-blue-700 border border-blue-100">
                                {{ $order->items->count() }} Produk
                            </span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border border-slate-200 rounded-xl overflow-hidden">
                                <thead class="bg-slate-50 text-slate-600">
                                    <tr class="text-left">
                                        <th class="border px-3 py-2 font-semibold">No</th>
                                        <th class="border px-3 py-2 font-semibold">Produk</th>
                                        <th class="border px-3 py-2 font-semibold">Type</th>
                                        <th class="border px-3 py-2 font-semibold">Volume / Ukuran</th>
                                        <th class="border px-3 py-2 font-semibold">Quantity</th>
                                        <th class="border px-3 py-2 font-semibold">Spesifikasi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($order->items as $item)
                                        <tr class="hover:bg-slate-50">
                                            <td class="border px-3 py-2">{{ $loop->iteration }}</td>

                                            <td class="border px-3 py-2 font-semibold text-slate-900">
                                                {{ $item->product->product_name ?? '-' }}
                                            </td>

                                            <td class="border px-3 py-2">
                                                {{ $item->variant->type_name ?? '-' }}
                                            </td>

                                            <td class="border px-3 py-2">
                                                @if($item->selectedVolume)
                                                    {{ $item->selectedVolume->volume_value }} {{ $item->selectedVolume->unit }}
                                                @else
                                                    {{ $item->volume }}
                                                @endif
                                            </td>

                                            <td class="border px-3 py-2">
                                                {{ $item->quantity }} unit
                                            </td>

                                            <td class="border px-3 py-2">
                                                {{ $item->product_spec ?: '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="border px-3 py-4 text-center text-gray-500">
                                                Belum ada produk pada order ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Verifikasi Order</h3>

                    <form method="POST" action="/admin/orders/{{ $order->id }}/verify" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium mb-1">Status Verifikasi</label>
                            <select name="status_verify" class="w-full rounded-xl border-slate-200">
                                <option value="approved" {{ $order->status_verify == 'approved' ? 'selected' : '' }}>
                                    Approve
                                </option>
                                <option value="rejected" {{ $order->status_verify == 'rejected' ? 'selected' : '' }}>
                                    Reject
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Catatan Verifikasi</label>
                            <textarea name="verify_note"
                                      rows="4"
                                      class="w-full rounded-xl border-slate-200"
                                      placeholder="Tambahkan catatan approve / reject...">{{ $order->verify_note }}</textarea>
                        </div>

                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                            Simpan Verifikasi
                        </button>
                    </form>
                </div>

            </div>

            <div class="space-y-6">

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Dokumen Kontrak</h3>

                    @if($order->status_verify !== 'approved')
                        <div class="bg-orange-50 border border-orange-100 text-orange-700 rounded-xl p-4 text-sm">
                            <p class="font-semibold">Kontrak belum tersedia</p>
                            <p class="mt-1">
                                Dokumen kontrak akan otomatis dibuat oleh sistem setelah order disetujui admin.
                            </p>
                        </div>
                    @else
                        @if($order->contract_file)
                            <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl">📄</div>
                                    <div>
                                        <p class="text-sm font-semibold text-green-700">
                                            Kontrak otomatis berhasil dibuat
                                        </p>
                                        <p class="text-xs text-green-600 mt-1">
                                            Dokumen ini digenerate otomatis setelah order disetujui.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <a href="{{ asset('storage/'.$order->contract_file) }}"
                                   target="_blank"
                                   class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                    Lihat Kontrak
                                </a>

                                <a href="{{ asset('storage/'.$order->contract_file) }}"
                                   download
                                   class="block text-center border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl text-sm font-semibold">
                                    Download Kontrak
                                </a>
                            </div>
                        @else
                            <div class="bg-yellow-50 border border-yellow-100 text-yellow-700 rounded-xl p-4 text-sm">
                                <p class="font-semibold">Kontrak belum tergenerate</p>
                                <p class="mt-1">
                                    Order sudah disetujui, namun file kontrak belum tersedia. Simpan verifikasi kembali untuk membuat kontrak otomatis.
                                </p>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Estimasi Produksi Admin</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Hari Tersedia</span>
                            <span class="font-bold text-slate-900">{{ $availableDays }} hari</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Estimasi Produksi</span>
                            <span class="font-bold text-slate-900">{{ $totalProductionDays }} hari</span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Estimasi SDM</span>
                            <span class="font-bold text-slate-900">{{ $totalSdm }} pekerja</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status Produksi</span>
                            <span class="px-3 py-1 rounded-full border text-xs font-bold {{ $productionClass }}">
                                {{ $productionStatus }}
                            </span>
                        </div>

                        <hr>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Harga Dasar</span>
                            <span class="font-bold text-slate-900">
                                Rp {{ number_format($totalBasePrice, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span class="text-gray-500">Biaya Percepatan</span>
                            <span class="font-bold text-slate-900">
                                Rp {{ number_format($totalAccelerationFee, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="flex justify-between text-base">
                            <span class="font-bold text-slate-900">Total Estimasi</span>
                            <span class="font-black text-blue-700">
                                Rp {{ number_format($totalEstimatedCost, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-5">
                        <p class="font-semibold text-slate-900 mb-2">Detail Produk</p>

                        <div class="space-y-3">
                            @forelse($productionEstimates as $estimate)
                                <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                                    <p class="font-semibold text-slate-900">
                                        {{ $estimate['product'] }}
                                    </p>

                                    <p class="text-xs text-gray-500 mt-1">
                                        Qty {{ $estimate['quantity'] }} unit •
                                        Kapasitas {{ $estimate['capacity_per_day'] }} unit/hari •
                                        Estimasi {{ $estimate['production_days'] }} hari
                                    </p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada estimasi produk.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-lg font-bold text-slate-900 mb-4">Project</h3>

                    @if($order->status_verify !== 'approved')
                        <div class="bg-slate-50 border border-slate-200 text-slate-600 rounded-xl p-4 text-sm">
                            Project dapat dibuat setelah order disetujui.
                        </div>
                    @else
                        @if($order->project)
                            <div class="bg-green-50 border border-green-100 rounded-xl p-4 mb-4">
                                <p class="text-sm font-semibold text-green-700">
                                    Project sudah dibuat
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    {{ $order->project->project_name }}
                                </p>
                            </div>

                            <a href="/admin/projects/{{ $order->project->id }}"
                               class="block text-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                Lihat Project
                            </a>
                        @else
                            <a href="/admin/projects/create/{{ $order->id }}"
                               class="block text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold">
                                Buat Project dari Order Ini
                            </a>
                        @endif
                    @endif
                </div>

                <div class="bg-slate-900 text-white rounded-2xl shadow-sm p-6">
                    <h3 class="text-lg font-bold mb-3">Status Workflow</h3>

                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span>Order diterima</span>
                            <span>✔</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Verifikasi admin</span>
                            <span>{{ $order->status_verify === 'approved' ? '✔' : '•' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Kontrak otomatis</span>
                            <span>{{ $order->contract_file ? '✔' : '•' }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Project</span>
                            <span>{{ $order->project ? '✔' : '•' }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>