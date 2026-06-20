<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Daftar Order</h2>
        <p class="text-sm text-gray-500 mt-1">
            Riwayat seluruh pesanan produk yang Anda buat.
        </p>
    </x-slot>

    <div class="space-y-6">

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center">
            <a href="/client/orders/create"
               class="inline-flex items-center px-4 py-2 rounded-xl bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600">
                + Buat Pesanan
            </a>

            <a href="/reports/client/orders/download"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-sm font-semibold hover:bg-slate-50">
                ⬇ Export
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- SEARCH FILTER --}}
            <div class="p-4 border-b border-slate-200 flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <input type="text"
                           placeholder="Cari nomor order, produk, project..."
                           class="w-full rounded-xl border-slate-200 bg-slate-50 pl-4 text-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                <button class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm font-semibold hover:bg-slate-50">
                    Filter
                </button>
            </div>

            {{-- TABLE --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr class="border-b border-slate-200 text-left">
                            <th class="px-4 py-3 font-semibold">No. Order</th>
                            <th class="px-4 py-3 font-semibold">Produk</th>
                            <th class="px-4 py-3 font-semibold">Volume</th>
                            <th class="px-4 py-3 font-semibold">Project</th>
                            <th class="px-4 py-3 font-semibold">Tanggal</th>
                            <th class="px-4 py-3 font-semibold">Status</th>
                            <th class="px-4 py-3 font-semibold">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-4 py-4 font-semibold text-slate-900">
                                    ORD-{{ $order->created_at->format('Y') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-4 py-4">
                                    {{ $order->product->product_name }}
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $order->volume }} unit
                                </td>

                                <td class="px-4 py-4">
                                    {{ $order->project_name }}
                                </td>

                                <td class="px-4 py-4 text-slate-600">
                                    {{ $order->created_at->format('d M Y') }}
                                </td>

                                <td class="px-4 py-4">
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
                                </td>

                                <td class="px-4 py-4">
                                    <a href="/client/orders/{{ $order->id }}"
                                       class="inline-flex items-center gap-2 text-slate-900 font-semibold hover:text-blue-600">
                                        👁 Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center">
                                    <div class="text-4xl mb-2">🧾</div>
                                    <p class="font-semibold text-slate-900">Belum ada order</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Silakan buat pesanan pertama Anda.
                                    </p>

                                    <a href="/client/orders/create"
                                       class="mt-4 inline-flex items-center px-4 py-2 rounded-xl bg-orange-500 text-white text-sm font-semibold hover:bg-orange-600">
                                        + Buat Pesanan
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER / PAGINATION STYLE --}}
            <div class="px-4 py-4 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-3">
                <p class="text-sm text-gray-500">
                    Menampilkan {{ $orders->count() }} order
                </p>

                <div class="flex items-center gap-2">
                    <button class="w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-500">
                        ‹
                    </button>
                    <button class="w-9 h-9 rounded-xl bg-[#0F172A] text-white font-semibold">
                        1
                    </button>
                    <button class="w-9 h-9 rounded-xl border border-slate-200 bg-white text-slate-500">
                        ›
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>