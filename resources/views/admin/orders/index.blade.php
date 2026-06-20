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

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h3 class="font-bold text-lg text-slate-900">Daftar Pesanan</h3>
                    <p class="text-sm text-gray-500">
                        Total {{ $orders->count() }} pesanan masuk
                    </p>
                </div>

                <div class="flex gap-3">
                    <input type="text"
                           placeholder="Cari client / project..."
                           class="rounded-xl border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">

                    <button class="px-4 py-2 rounded-xl border border-slate-200 bg-white text-sm font-semibold hover:bg-slate-50">
                        Filter
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-slate-600">
                        <tr class="border-b border-slate-200 text-left">
                            <th class="px-5 py-4 font-semibold">No. Order</th>
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
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $order->user->name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->company_name ?? '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">
                                            {{ $order->product->product_name }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $order->variant->type_name ?? '-' }}
                                            @if($order->selectedVolume)
                                                • {{ $order->selectedVolume->volume_value }} {{ $order->selectedVolume->unit }}
                                            @endif
                                        </p>
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
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <div class="text-4xl mb-3">📭</div>
                                    <p class="font-semibold text-slate-900">Belum ada order masuk</p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Order dari client akan tampil di halaman ini.
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