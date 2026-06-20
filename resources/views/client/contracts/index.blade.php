<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">Kontrak</h2>
        <p class="text-sm text-gray-500 mt-1">
            Daftar kontrak pemesanan dan dokumen pendukung.
        </p>
    </x-slot>

    <div class="space-y-6">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
            @forelse($orders as $order)
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:shadow-lg transition">
                    <div class="flex justify-between gap-4">

                        <div class="flex gap-4">
                            <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center text-xl">
                                📄
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    KTR-{{ $order->created_at->format('Y') }}-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </p>

                                <h3 class="text-lg font-semibold text-slate-900">
                                    Kontrak {{ $order->project_name }}
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $order->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        <div>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $order->status_verify === 'approved' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $order->status_verify === 'approved' ? 'Disetujui' : 'Terkirim' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-between items-end">
                        <div>
                            <p class="text-sm text-gray-500">Produk</p>
                            <p class="font-semibold text-slate-900">
                                {{ $order->product->product_name }}
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ asset('storage/' . $order->contract_file) }}"
                               target="_blank"
                               class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 flex items-center justify-center">
                                👁️
                            </a>

                            <a href="{{ asset('storage/' . $order->contract_file) }}"
                               download
                               class="w-10 h-10 rounded-xl border border-slate-200 bg-slate-50 hover:bg-slate-100 flex items-center justify-center">
                                ⬇️
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-2 bg-white rounded-2xl border border-dashed border-slate-300 p-10 text-center">
                    <div class="text-4xl mb-3">📄</div>
                    <h3 class="font-semibold text-slate-900">Belum ada kontrak</h3>
                    <p class="text-gray-500 text-sm mt-1">
                        Kontrak akan muncul setelah admin mengunggah dokumen kontrak untuk order Anda.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>