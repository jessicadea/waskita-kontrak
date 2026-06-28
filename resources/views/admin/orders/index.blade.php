<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Pesanan Masuk</h2>
        <p class="text-sm text-gray-500 mt-1">
            Kelola dan verifikasi seluruh pesanan dari client.
        </p>
    </x-slot>

    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Filter Pesanan</h3>
                    <p class="text-sm text-gray-500">
                        Cari pesanan berdasarkan tanggal, status, client, produk, atau nama project.
                    </p>
                </div>

                <span class="px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold w-fit">
                    {{ $orders->count() }} Pesanan
                </span>
            </div>

            <form method="GET" action="{{ route('admin.orders.index') }}"
                  class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">

                <div class="md:col-span-2">
                    <label class="text-sm font-semibold text-slate-700">Pencarian</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Client, project, lokasi, produk..."
                           class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Tanggal Awal</label>
                    <input type="date"
                           name="start_date"
                           value="{{ request('start_date') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Tanggal Akhir</label>
                    <input type="date"
                           name="end_date"
                           value="{{ request('end_date') }}"
                           class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <div>
                    <label class="text-sm font-semibold text-slate-700">Status</label>
                    <select name="status_verify"
                            class="mt-1 w-full rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status_verify') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="approved" {{ request('status_verify') === 'approved' ? 'selected' : '' }}>
                            Disetujui
                        </option>
                        <option value="rejected" {{ request('status_verify') === 'rejected' ? 'selected' : '' }}>
                            Ditolak
                        </option>
                    </select>
                </div>

                <div class="md:col-span-5 flex flex-wrap gap-3">
                    <button type="submit"
                            class="px-5 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                        Terapkan Filter
                    </button>

                    <a href="{{ route('admin.orders.index') }}"
                       class="px-5 py-2 rounded-xl border border-slate-200 bg-white text-slate-700 text-sm font-semibold hover:bg-slate-50">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Daftar Pesanan</h3>
                    <p class="text-sm text-gray-500">
                        Menampilkan {{ $orders->count() }} pesanan
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr class="border-b border-slate-200 text-left">
                            <th class="px-5 py-4 font-semibold">No. Order</th>
                            <th class="px-5 py-4 font-semibold">Tanggal</th>
                            <th class="px-5 py-4 font-semibold">Client</th>
                            <th class="px-5 py-4 font-semibold">Produk</th>
                            <th class="px-5 py-4 font-semibold">Project</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-4 font-semibold text-slate-900">
                                    ORD-{{ $order->created_at->format('Y') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-semibold text-slate-900">
                                        {{ $order->created_at->format('d M Y') }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $order->created_at->format('H:i') }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $order->user->name ?? '-' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->company_name ?? '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div>
                                        @if($order->items->count() > 0)
                                            <p class="font-semibold text-slate-900">
                                                {{ $order->items->count() }} Produk
                                            </p>

                                            <div class="text-xs text-gray-500 mt-1 space-y-1">
                                                @foreach($order->items->take(2) as $item)
                                                    <p>
                                                        {{ $item->product->product_name ?? '-' }}
                                                        @if($item->variant)
                                                            • {{ $item->variant->type_name }}
                                                        @endif
                                                        @if($item->selectedVolume)
                                                            • {{ $item->selectedVolume->volume_value }} {{ $item->selectedVolume->unit }}
                                                        @endif
                                                    </p>
                                                @endforeach

                                                @if($order->items->count() > 2)
                                                    <p class="text-blue-600 font-semibold">
                                                        +{{ $order->items->count() - 2 }} produk lainnya
                                                    </p>
                                                @endif
                                            </div>
                                        @else
                                            <p class="font-semibold text-slate-900">
                                                {{ $order->product->product_name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $order->variant->type_name ?? '-' }}
                                                @if($order->selectedVolume)
                                                    • {{ $order->selectedVolume->volume_value }} {{ $order->selectedVolume->unit }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="font-medium text-slate-900">{{ $order->project_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $order->project_location }}</p>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex flex-col gap-2 items-start">
                                        @if($order->status_verify === 'approved')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                                Disetujui
                                            </span>
                                        @elseif($order->status_verify === 'pending')
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                                <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                                Ditolak
                                            </span>
                                        @endif

                                        @if($order->requires_acceleration)
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                ⚡ Percepatan Produksi
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <a href="/admin/orders/{{ $order->id }}"
                                       class="inline-flex items-center px-4 py-2 rounded-xl bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="text-4xl mb-3">📭</div>
                                    <p class="font-semibold text-slate-900">Data pesanan tidak ditemukan</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Coba ubah kata kunci atau rentang tanggal filter.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>