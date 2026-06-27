<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Detail Order
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">

            <p><b>Perusahaan:</b> {{ $order->company_name }}</p>
            <p><b>Jenis Perusahaan:</b> {{ $order->company_type }}</p>
            <p><b>Nama Project:</b> {{ $order->project_name }}</p>
            <p><b>Lokasi:</b> {{ $order->project_location }}</p>
            <p><b>Kondisi Pengiriman:</b> {{ $order->delivery_cond }}</p>
            <p><b>Tanggal Pengiriman:</b> {{ $order->delivery_date }}</p>
            <p><b>Status:</b> {{ ucfirst($order->status_verify) }}</p>

            @if($order->verify_note)
                <p><b>Catatan Verifikasi:</b> {{ $order->verify_note }}</p>
            @endif

            <div class="mt-4">
                <b>Daftar Produk:</b>

                <div class="mt-2 overflow-x-auto">
                    <table class="w-full text-sm border border-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="border px-3 py-2 text-left">No</th>
                                <th class="border px-3 py-2 text-left">Produk</th>
                                <th class="border px-3 py-2 text-left">Type</th>
                                <th class="border px-3 py-2 text-left">Volume / Ukuran</th>
                                <th class="border px-3 py-2 text-left">Quantity</th>
                                <th class="border px-3 py-2 text-left">Spesifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td class="border px-3 py-2">{{ $loop->iteration }}</td>
                                    <td class="border px-3 py-2">{{ $item->product->product_name ?? '-' }}</td>
                                    <td class="border px-3 py-2">{{ $item->variant->type_name ?? '-' }}</td>
                                    <td class="border px-3 py-2">
                                        @if($item->selectedVolume)
                                            {{ $item->selectedVolume->volume_value }} {{ $item->selectedVolume->unit }}
                                        @else
                                            {{ $item->volume }}
                                        @endif
                                    </td>
                                    <td class="border px-3 py-2">{{ $item->quantity }}</td>
                                    <td class="border px-3 py-2">{{ $item->product_spec }}</td>
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

            @if($order->contract_file)
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $order->contract_file) }}"
                       target="_blank"
                       class="text-blue-600 font-medium">
                        📄 Download Kontrak
                    </a>
                </div>
            @endif

            @if($order->project)
                <div class="mt-6">
                    <a href="/client/projects/{{ $order->project->id }}/monitoring"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                        📊 Lihat Monitoring Project
                    </a>
                </div>
            @endif

            <div class="mt-6">
                <a href="/client/orders" class="text-gray-600 hover:underline">
                    ← Kembali
                </a>
            </div>

        </div>
    </div>
</x-app-layout>