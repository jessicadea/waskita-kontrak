<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Detail Order
        </h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow rounded-lg p-6">

            <p><b>Produk:</b> {{ $order->product->product_name }}</p>
            <p><b>Perusahaan:</b> {{ $order->company_name }}</p>
            <p><b>Jenis Perusahaan:</b> {{ $order->company_type }}</p>
            <p><b>Nama Project:</b> {{ $order->project_name }}</p>
            <p><b>Lokasi:</b> {{ $order->project_location }}</p>
            <p><b>Spesifikasi:</b> {{ $order->product_spec }}</p>
            <p><b>Volume:</b> {{ $order->volume }}</p>
            <p><b>Kondisi Pengiriman:</b> {{ $order->delivery_cond }}</p>
            <p><b>Tanggal Pengiriman:</b> {{ $order->delivery_date }}</p>
            <p><b>Status:</b> {{ ucfirst($order->status_verify) }}</p>

            @if($order->verify_note)
                <p><b>Catatan Verifikasi:</b> {{ $order->verify_note }}</p>
            @endif

            {{-- DOWNLOAD KONTRAK --}}
            @if($order->contract_file)
                <div class="mt-3">
                    <a href="{{ asset('storage/' . $order->contract_file) }}" 
                       target="_blank" 
                       class="text-blue-600 font-medium">
                        📄 Download Kontrak
                    </a>
                </div>
            @endif

            {{-- MONITORING PROJECT --}}
            @if($order->project)
                <div class="mt-6">
                    <a href="/client/projects/{{ $order->project->id }}/monitoring"
                       class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                        📊 Lihat Monitoring Project
                    </a>
                </div>
            @endif

            {{-- BACK --}}
            <div class="mt-6">
                <a href="/client/orders" class="text-gray-600 hover:underline">
                    ← Kembali
                </a>
            </div>

        </div>
    </div>
</x-app-layout>