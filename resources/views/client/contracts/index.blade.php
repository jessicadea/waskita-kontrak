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
                            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
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
                               class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-200 flex items-center justify-center transition"
                               title="Lihat Kontrak">
                                <svg class="w-5 h-5 text-blue-600"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5
                                             c4.478 0 8.268 2.943 9.542 7
                                             -1.274 4.057-5.064 7-9.542 7
                                             -4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ asset('storage/' . $order->contract_file) }}"
                               download
                               class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-blue-50 hover:border-blue-200 flex items-center justify-center transition"
                               title="Unduh Kontrak">
                                <svg class="w-5 h-5 text-blue-600"
                                     fill="none"
                                     stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 3v12m0 0l-4-4m4 4l4-4"/>
                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-2 bg-white rounded-2xl border border-dashed border-slate-300 p-10 text-center">
                    <div class="mx-auto w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400"
                             fill="none"
                             stroke="currentColor"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <h3 class="font-semibold text-slate-900">Belum ada kontrak</h3>
                    <p class="text-gray-500 text-sm mt-1">
                        Kontrak akan muncul setelah admin mengunggah dokumen kontrak untuk order Anda.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>